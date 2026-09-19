<x-app-layout>
    <x-slot name="header">System Settings</x-slot>

    <div class="bg-white rounded-lg shadow p-6 max-w-2xl">
        <form method="POST" action="{{ route('settings.update') }}">
            @csrf
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Application Name</label>
                <input type="text" name="app_name" value="{{ config('app.name') }}" class="w-full border border-gray-300 rounded-lg px-3 py-2">
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Environment</label>
                <input type="text" value="{{ app()->environment() }}" disabled class="w-full border border-gray-300 rounded-lg px-3 py-2 bg-gray-50">
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Debug Mode</label>
                <input type="text" value="{{ config('app.debug') ? 'Enabled' : 'Disabled' }}" disabled class="w-full border border-gray-300 rounded-lg px-3 py-2 bg-gray-50">
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">PHP Version</label>
                <input type="text" value="{{ phpversion() }}" disabled class="w-full border border-gray-300 rounded-lg px-3 py-2 bg-gray-50">
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Laravel Version</label>
                <input type="text" value="{{ app()->version() }}" disabled class="w-full border border-gray-300 rounded-lg px-3 py-2 bg-gray-50">
            </div>
            <div class="flex gap-3">
                <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700">Save</button>
            </div>
        </form>
    </div>
</x-app-layout>
