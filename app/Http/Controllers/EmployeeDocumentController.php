<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\EmployeeDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EmployeeDocumentController extends Controller
{
    public function index(Request $request)
    {
        $employeeIds = auth()->user()->accessibleEmployees()->pluck('employee_id');

        $query = EmployeeDocument::with(['employee', 'uploader'])
            ->whereIn('employee_id', $employeeIds);

        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhereHas('employee', function ($e) use ($search) {
                      $e->where('first_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%");
                  });
            });
        }

        $documents = $query->latest()->paginate(10)->appends(request()->query());

        return view('documents.index', compact('documents'));
    }

    public function create()
    {
        $employees = Employee::orderBy('first_name')->get();

        return view('documents.create', compact('employees'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,employee_id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
            'file' => 'required|file|max:10240',
        ]);

        Employee::findOrFail($validated['employee_id']);

        $uploaded = $request->file('file');
        $path = $uploaded->store('employee_documents', 'public');

        EmployeeDocument::create([
            'employee_id' => $validated['employee_id'],
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'file_path' => $path,
            'original_name' => $uploaded->getClientOriginalName(),
            'mime_type' => $uploaded->getMimeType(),
            'file_size' => $uploaded->getSize(),
            'uploaded_by' => auth()->id(),
        ]);

        return redirect()->route('documents.index')->with('success', 'Document uploaded successfully.');
    }

    public function download(EmployeeDocument $document)
    {
        $this->authorizeScope($document);

        if (!Storage::disk('public')->exists($document->file_path)) {
            return redirect()->route('documents.index')->with('error', 'Document file not found.');
        }

        return Storage::disk('public')->download($document->file_path, $document->original_name);
    }

    public function destroy(EmployeeDocument $document)
    {
        $this->authorizeScope($document);

        Storage::disk('public')->delete($document->file_path);
        $document->delete();

        return redirect()->route('documents.index')->with('success', 'Document deleted successfully.');
    }

    private function authorizeScope(EmployeeDocument $document): void
    {
        $accessible = auth()->user()
            ->accessibleEmployees()
            ->whereKey($document->employee_id)
            ->exists();

        abort_unless($accessible, 403, 'You do not have access to this document.');
    }
}
