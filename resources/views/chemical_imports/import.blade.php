<x-app-layout>
    <div class="max-w-4xl mx-auto p-6 bg-white shadow rounded">
        <h2 class="text-2xl font-semibold mb-4">นำข้อมูลเข้า</h2>

        {{-- Success --}}
        @if (session('success'))
            <div class="bg-green-100 text-green-800 p-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        {{-- General Error --}}
        @if (session('error'))
            <div class="bg-red-100 text-red-800 p-3 rounded mb-4">
                {{ session('error') }}
            </div>
        @endif

        {{-- Import Errors (array) --}}
        @if (session('import_errors'))
            <div class="bg-red-100 text-red-800 p-3 rounded mb-4">
                <h5 class="font-semibold mb-2">ข้อผิดพลาดในการนำเข้า:</h5>
                <ul class="list-disc pl-6 space-y-1">
                    @foreach (session('import_errors') as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Validation Errors --}}
        @if ($errors->any())
            <div class="bg-red-100 text-red-800 p-3 rounded mb-4">
                <ul class="list-disc pl-6 space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('chemical_imports.import') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-4">
                <label for="file" class="block font-medium text-sm text-gray-700">อัปโหลดไฟล์ Excel</label>
                <input
                    type="file"
                    id="file"
                    name="file"
                    required
                    class="mt-1 block w-full border rounded px-3 py-2"
                >
            </div>

            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
                นำเข้า
            </button>
        </form>
    </div>
</x-app-layout>
