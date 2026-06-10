<x-app-layout>
    <div class="max-w-4xl mx-auto p-6 bg-white shadow rounded">
        <h2 class="text-2xl font-semibold mb-4">นำข้อมูลเข้า</h2>

        @if(session('success'))
            <div class="bg-green-100 text-green-800 p-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        <form method="POST" action="{{ route('import.production-registration') }}" enctype="multipart/form-data">
            @csrf
            <div class="mb-4">
                <label class="block font-medium text-sm text-gray-700">อัปโหลดไฟล์ Excel</label>
                <input type="file" name="excel_file" required class="mt-1 block w-full border rounded px-3 py-2">
            </div>
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">นำเข้า</button>
        </form>
    </div>
</x-app-layout>
