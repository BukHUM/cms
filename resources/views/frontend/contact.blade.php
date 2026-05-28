@extends('layouts.frontend')

@section('title', 'ติดต่อเรา')
@section('description', 'ติดต่อทีมพัฒนาเพื่อสอบถามข้อมูลหรือขอความช่วยเหลือ')

@section('content')
<!-- Success/Error Messages -->
@if(session('success'))
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-6">
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg flex items-center justify-between" role="alert">
            <div class="flex items-center">
                <i class="fas fa-check-circle mr-2"></i>
                {{ session('success') }}
            </div>
            <button type="button" class="text-green-700 hover:text-green-900" onclick="this.parentElement.parentElement.style.display='none'">
                <i class="fas fa-times"></i>
            </button>
        </div>
    </div>
@endif

@if(session('error'))
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-6">
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg flex items-center justify-between" role="alert">
            <div class="flex items-center">
                <i class="fas fa-exclamation-circle mr-2"></i>
                {{ session('error') }}
            </div>
            <button type="button" class="text-red-700 hover:text-red-900" onclick="this.parentElement.parentElement.style.display='none'">
                <i class="fas fa-times"></i>
            </button>
        </div>
    </div>
@endif

<!-- Hero Section -->
<section class="hero-section relative min-h-screen flex items-center justify-center overflow-hidden">
    <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
            <!-- Content -->
            <div class="text-slate-800">
                <h1 class="text-5xl lg:text-6xl font-bold mb-6 leading-tight">
                    ติดต่อเรา
                </h1>
                <p class="text-xl text-slate-600 mb-8 leading-relaxed">
                    เราพร้อมให้บริการและตอบคำถามทุกข้อสงสัย
                    ติดต่อเราได้หลายช่องทาง
                </p>
            </div>
            
            <!-- Image -->
            <div class="relative">
                <div class="relative z-10 bg-white/10 backdrop-blur-lg rounded-3xl p-8 border border-white/20 shadow-2xl">
                    <img src="https://placehold.co/500x300/667eea/ffffff?text=Contact+Us" 
                         class="w-full rounded-2xl shadow-xl" alt="Contact Us">
                </div>
                <!-- Floating Elements -->
                <div class="absolute -top-4 -right-4 w-8 h-8 bg-yellow-400 rounded-full animate-bounce"></div>
                <div class="absolute -bottom-4 -left-4 w-6 h-6 bg-white/30 rounded-full animate-pulse"></div>
            </div>
        </div>
    </div>
</section>

<!-- Contact Form & Info -->
<section class="py-20 bg-slate-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
            <!-- Contact Form -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-2xl shadow-lg border border-slate-200">
                    <div class="bg-gradient-primary text-white p-6 rounded-t-2xl">
                        <h4 class="text-xl font-semibold flex items-center">
                            <i class="fas fa-envelope mr-3"></i>
                            ส่งข้อความถึงเรา
                        </h4>
                    </div>
                    <div class="p-8">
                        <form action="{{ route('contact.send') }}" method="POST" class="space-y-6">
                            @csrf
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="name" class="block text-sm font-semibold text-slate-700 mb-2">ชื่อ-นามสกุล *</label>
                                    <input type="text" class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-primary focus:border-transparent @error('name') border-red-500 @enderror" 
                                           id="name" name="name" value="{{ old('name') }}" required>
                                    @error('name')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                
                                <div>
                                    <label for="email" class="block text-sm font-semibold text-slate-700 mb-2">อีเมล *</label>
                                    <input type="email" class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-primary focus:border-transparent @error('email') border-red-500 @enderror" 
                                           id="email" name="email" value="{{ old('email') }}" required>
                                    @error('email')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                
                                <div>
                                    <label for="phone" class="block text-sm font-semibold text-slate-700 mb-2">เบอร์โทรศัพท์</label>
                                    <input type="tel" class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-primary focus:border-transparent @error('phone') border-red-500 @enderror" 
                                           id="phone" name="phone" value="{{ old('phone') }}">
                                    @error('phone')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                
                                <div>
                                    <label for="subject" class="block text-sm font-semibold text-slate-700 mb-2">หัวข้อ *</label>
                                    <select class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-primary focus:border-transparent @error('subject') border-red-500 @enderror" 
                                            id="subject" name="subject" required>
                                        <option value="">เลือกหัวข้อ</option>
                                        <option value="general" {{ old('subject') == 'general' ? 'selected' : '' }}>คำถามทั่วไป</option>
                                        <option value="support" {{ old('subject') == 'support' ? 'selected' : '' }}>ขอความช่วยเหลือ</option>
                                        <option value="sales" {{ old('subject') == 'sales' ? 'selected' : '' }}>สอบถามราคา</option>
                                        <option value="technical" {{ old('subject') == 'technical' ? 'selected' : '' }}>ปัญหาทางเทคนิค</option>
                                        <option value="other" {{ old('subject') == 'other' ? 'selected' : '' }}>อื่นๆ</option>
                                    </select>
                                    @error('subject')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                            
                            <div>
                                <label for="message" class="block text-sm font-semibold text-slate-700 mb-2">ข้อความ *</label>
                                <textarea class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-primary focus:border-transparent @error('message') border-red-500 @enderror" 
                                          id="message" name="message" rows="5" required>{{ old('message') }}</textarea>
                                @error('message')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <button type="submit" class="w-full bg-gradient-primary text-white px-8 py-4 rounded-xl font-semibold text-lg hover:shadow-lg hover:-translate-y-1 transition-all duration-300 flex items-center justify-center gap-3">
                                    <i class="fas fa-paper-plane"></i>
                                    ส่งข้อความ
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            
            <!-- Contact Information -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-2xl shadow-lg border border-slate-200 h-full">
                    <div class="bg-gradient-success text-white p-6 rounded-t-2xl">
                        <h4 class="text-xl font-semibold flex items-center">
                            <i class="fas fa-info-circle mr-3"></i>
                            ข้อมูลติดต่อ
                        </h4>
                    </div>
                    <div class="p-8 space-y-8">
                        <div>
                            <h6 class="text-lg font-semibold text-slate-800 mb-3 flex items-center">
                                <i class="fas fa-map-marker-alt text-primary mr-3"></i>
                                ที่อยู่
                            </h6>
                            <p class="text-slate-600 leading-relaxed">
                                123 ถนนสุขุมวิท<br>
                                แขวงคลองตัน เขตวัฒนา<br>
                                กรุงเทพมหานคร 10110
                            </p>
                        </div>
                        
                        <div>
                            <h6 class="text-lg font-semibold text-slate-800 mb-3 flex items-center">
                                <i class="fas fa-phone text-primary mr-3"></i>
                                โทรศัพท์
                            </h6>
                            <p class="text-slate-600">
                                <a href="tel:+6621234567" class="hover:text-primary transition-colors">+66 2-123-4567</a><br>
                                <a href="tel:+66812345678" class="hover:text-primary transition-colors">+66 81-234-5678</a>
                            </p>
                        </div>
                        
                        <div>
                            <h6 class="text-lg font-semibold text-slate-800 mb-3 flex items-center">
                                <i class="fas fa-envelope text-primary mr-3"></i>
                                อีเมล
                            </h6>
                            <p class="text-slate-600">
                                <a href="mailto:info@example.com" class="hover:text-primary transition-colors">info@example.com</a><br>
                                <a href="mailto:support@example.com" class="hover:text-primary transition-colors">support@example.com</a>
                            </p>
                        </div>
                        
                        <div>
                            <h6 class="text-lg font-semibold text-slate-800 mb-3 flex items-center">
                                <i class="fas fa-clock text-primary mr-3"></i>
                                เวลาทำการ
                            </h6>
                            <p class="text-slate-600 leading-relaxed">
                                จันทร์ - ศุกร์: 08:00 - 18:00<br>
                                เสาร์: 09:00 - 17:00<br>
                                อาทิตย์: ปิดทำการ
                            </p>
                        </div>
                        
                        <div>
                            <h6 class="text-lg font-semibold text-slate-800 mb-3 flex items-center">
                                <i class="fas fa-share-alt text-primary mr-3"></i>
                                ติดตามเรา
                            </h6>
                            <div class="flex space-x-4">
                                <a href="#" class="w-12 h-12 bg-blue-600 text-white rounded-full flex items-center justify-center hover:bg-blue-700 transition-colors">
                                    <i class="fab fa-facebook-f"></i>
                                </a>
                                <a href="#" class="w-12 h-12 bg-sky-500 text-white rounded-full flex items-center justify-center hover:bg-sky-600 transition-colors">
                                    <i class="fab fa-twitter"></i>
                                </a>
                                <a href="#" class="w-12 h-12 bg-pink-500 text-white rounded-full flex items-center justify-center hover:bg-pink-600 transition-colors">
                                    <i class="fab fa-instagram"></i>
                                </a>
                                <a href="#" class="w-12 h-12 bg-blue-700 text-white rounded-full flex items-center justify-center hover:bg-blue-800 transition-colors">
                                    <i class="fab fa-linkedin-in"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- FAQ Section -->
<section class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-4xl lg:text-5xl font-bold text-slate-800 mb-6">คำถามที่พบบ่อย</h2>
            <p class="text-xl text-slate-600 max-w-3xl mx-auto">
                คำตอบสำหรับคำถามที่ลูกค้าสอบถามบ่อย
            </p>
        </div>
        
        <div class="max-w-4xl mx-auto">
            <div class="space-y-4">
                <!-- FAQ 1 -->
                <div class="bg-white border border-slate-200 rounded-2xl shadow-sm hover:shadow-md transition-shadow">
                    <button class="w-full px-8 py-6 text-left flex items-center justify-between focus:outline-none focus:ring-2 focus:ring-primary focus:ring-opacity-50" 
                            onclick="toggleFAQ('faq1')">
                        <h3 class="text-lg font-semibold text-slate-800">ระบบมีความปลอดภัยแค่ไหน?</h3>
                        <i class="fas fa-chevron-down text-slate-500 transform transition-transform" id="faq1-icon"></i>
                    </button>
                    <div class="px-8 pb-6 text-slate-600 leading-relaxed hidden" id="faq1-content">
                        ระบบของเราใช้เทคโนโลยีการเข้ารหัสระดับสูง และมีการสำรองข้อมูลอัตโนมัติ
                        เพื่อความปลอดภัยของข้อมูลลูกค้า
                    </div>
                </div>
                
                <!-- FAQ 2 -->
                <div class="bg-white border border-slate-200 rounded-2xl shadow-sm hover:shadow-md transition-shadow">
                    <button class="w-full px-8 py-6 text-left flex items-center justify-between focus:outline-none focus:ring-2 focus:ring-primary focus:ring-opacity-50" 
                            onclick="toggleFAQ('faq2')">
                        <h3 class="text-lg font-semibold text-slate-800">สามารถใช้งานบนมือถือได้หรือไม่?</h3>
                        <i class="fas fa-chevron-down text-slate-500 transform transition-transform" id="faq2-icon"></i>
                    </button>
                    <div class="px-8 pb-6 text-slate-600 leading-relaxed hidden" id="faq2-content">
                        ได้ครับ ระบบของเราออกแบบให้รองรับการใช้งานบนทุกอุปกรณ์
                        รวมถึงสมาร์ทโฟนและแท็บเล็ต
                    </div>
                </div>
                
                <!-- FAQ 3 -->
                <div class="bg-white border border-slate-200 rounded-2xl shadow-sm hover:shadow-md transition-shadow">
                    <button class="w-full px-8 py-6 text-left flex items-center justify-between focus:outline-none focus:ring-2 focus:ring-primary focus:ring-opacity-50" 
                            onclick="toggleFAQ('faq3')">
                        <h3 class="text-lg font-semibold text-slate-800">มีการสนับสนุนลูกค้าหรือไม่?</h3>
                        <i class="fas fa-chevron-down text-slate-500 transform transition-transform" id="faq3-icon"></i>
                    </button>
                    <div class="px-8 pb-6 text-slate-600 leading-relaxed hidden" id="faq3-content">
                        เรามีทีมสนับสนุนลูกค้าที่พร้อมให้บริการตลอด 24 ชั่วโมง
                        ตอบคำถามและแก้ไขปัญหาอย่างรวดเร็ว
                    </div>
                </div>
                
                <!-- FAQ 4 -->
                <div class="bg-white border border-slate-200 rounded-2xl shadow-sm hover:shadow-md transition-shadow">
                    <button class="w-full px-8 py-6 text-left flex items-center justify-between focus:outline-none focus:ring-2 focus:ring-primary focus:ring-opacity-50" 
                            onclick="toggleFAQ('faq4')">
                        <h3 class="text-lg font-semibold text-slate-800">ราคาแพ็คเกจเป็นอย่างไร?</h3>
                        <i class="fas fa-chevron-down text-slate-500 transform transition-transform" id="faq4-icon"></i>
                    </button>
                    <div class="px-8 pb-6 text-slate-600 leading-relaxed hidden" id="faq4-content">
                        เรามีแพ็คเกจที่หลากหลาย เหมาะสำหรับทุกขนาดธุรกิจ
                        ติดต่อเราเพื่อขอข้อมูลราคาที่เหมาะสมกับความต้องการ
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
// FAQ Toggle Function
function toggleFAQ(faqId) {
    const content = document.getElementById(faqId + '-content');
    const icon = document.getElementById(faqId + '-icon');
    
    if (content.classList.contains('hidden')) {
        content.classList.remove('hidden');
        icon.style.transform = 'rotate(180deg)';
    } else {
        content.classList.add('hidden');
        icon.style.transform = 'rotate(0deg)';
    }
}

// Form validation
document.querySelector('form').addEventListener('submit', function(e) {
    const requiredFields = this.querySelectorAll('[required]');
    let isValid = true;
    
    requiredFields.forEach(field => {
        if (!field.value.trim()) {
            field.classList.add('border-red-500');
            isValid = false;
        } else {
            field.classList.remove('border-red-500');
        }
    });
    
    if (!isValid) {
        e.preventDefault();
        alert('กรุณากรอกข้อมูลให้ครบถ้วน');
    }
});

// Phone number formatting
document.getElementById('phone').addEventListener('input', function(e) {
    let value = e.target.value.replace(/\D/g, '');
    if (value.length > 0) {
        if (value.startsWith('0')) {
            value = value.replace(/(\d{3})(\d{3})(\d{4})/, '$1-$2-$3');
        } else if (value.startsWith('66')) {
            value = value.replace(/(\d{2})(\d{2})(\d{3})(\d{4})/, '+$1 $2-$3-$4');
        }
    }
    e.target.value = value;
});
</script>
@endpush
