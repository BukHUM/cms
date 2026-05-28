<!-- Email Field Component -->
<div>
    <label for="{{ $name }}" class="block text-sm font-medium text-gray-700 mb-2">
        <i class="fas fa-envelope mr-2"></i>{{ $label }}
    </label>
    <input 
        type="email" 
        id="{{ $name }}" 
        name="{{ $name }}" 
        value="{{ old($name) }}"
        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent auth-focus auth-transition @error($name) border-red-500 @enderror"
        placeholder="{{ $placeholder }}"
        @if($required) required @endif
        autocomplete="{{ $autocomplete }}"
        @if($name === 'email') autofocus @endif
    >
    @error($name)
        <p class="mt-2 text-sm text-red-600">
            <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
        </p>
    @enderror
</div>
