<!-- Error Messages -->
@if ($errors->any())
    <div class="mt-6 p-4 bg-red-50 border border-red-200 rounded-lg auth-transition">
        <div class="flex">
            <div class="flex-shrink-0">
                <i class="fas fa-exclamation-triangle text-red-400"></i>
            </div>
            <div class="ml-3">
                <h3 class="text-sm font-medium text-red-800">เกิดข้อผิดพลาด</h3>
                <div class="mt-2 text-sm text-red-700">
                    <ul class="list-disc list-inside space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endif

<!-- Success Messages -->
@if (session('success'))
    <div class="mt-6 p-4 bg-green-50 border border-green-200 rounded-lg auth-transition">
        <div class="flex">
            <div class="flex-shrink-0">
                <i class="fas fa-check-circle text-green-400"></i>
            </div>
            <div class="ml-3">
                <p class="text-sm text-green-700">{{ session('success') }}</p>
            </div>
        </div>
    </div>
@endif

<!-- Info Messages -->
@if (session('info'))
    <div class="mt-6 p-4 bg-blue-50 border border-blue-200 rounded-lg auth-transition">
        <div class="flex">
            <div class="flex-shrink-0">
                <i class="fas fa-info-circle text-blue-400"></i>
            </div>
            <div class="ml-3">
                <p class="text-sm text-blue-700">{{ session('info') }}</p>
            </div>
        </div>
    </div>
@endif

<!-- Rate Limiting Warning -->
@if (session('rate_limit'))
    <div class="mt-6 p-4 bg-yellow-50 border border-yellow-200 rounded-lg auth-transition">
        <div class="flex">
            <div class="flex-shrink-0">
                <i class="fas fa-clock text-yellow-400"></i>
            </div>
            <div class="ml-3">
                <h3 class="text-sm font-medium text-yellow-800">คำเตือน</h3>
                <p class="text-sm text-yellow-700">{{ session('rate_limit') }}</p>
            </div>
        </div>
    </div>
@endif
