<!-- Submit Button Component -->
<button 
    type="submit" 
    class="w-full bg-{{ $color }}-600 text-white py-3 px-4 rounded-lg font-medium hover:bg-{{ $color }}-700 focus:ring-2 focus:ring-{{ $color }}-500 focus:ring-offset-2 auth-transition transform hover:scale-[1.02] active:scale-[0.98] auth-focus"
>
    <i class="{{ $icon }} mr-2"></i>
    {{ $text }}
</button>
