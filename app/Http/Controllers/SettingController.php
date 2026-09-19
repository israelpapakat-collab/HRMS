<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        return view('settings.index');
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'app_name' => 'required|string|max:255',
        ]);

        // Update .env or config settings (this is a simplified example)
        // In production, store settings in a database table

        return redirect()->route('settings.index')->with('success', 'Settings updated.');
    }
}
