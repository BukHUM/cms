<!-- Password Field Component -->
<div>
    <label for="{{ $name }}" class="block text-sm font-medium text-gray-700 mb-2">
        <i class="fas fa-lock mr-2"></i>{{ $label }}
    </label>
    <div class="relative">
        <input 
            type="password" 
            id="{{ $name }}" 
            name="{{ $name }}" 
            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent auth-focus auth-transition pr-12 @error($name) border-red-500 @enderror"
            placeholder="{{ $placeholder }}"
            @if($required) required @endif
            autocomplete="{{ $autocomplete }}"
        >
        @if($showToggle)
            <div 
                id="togglePassword"
                class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 hover:text-gray-700 cursor-pointer z-10 p-1 rounded"
                style="pointer-events: auto; user-select: none;"
                aria-label="แสดง/ซ่อนรหัสผ่าน"
            >
                <i class="fas fa-eye" id="passwordIcon"></i>
            </div>
        @endif
    </div>
    @error($name)
        <p class="mt-2 text-sm text-red-600">
            <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
        </p>
    @enderror
</div>
