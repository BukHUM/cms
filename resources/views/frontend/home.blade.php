@extends('layouts.frontend')

@section('title', 'หน้าแรก')
@section('description', 'ยินดีต้อนรับสู่ระบบจัดการข้อมูลที่ทันสมัยและใช้งานง่าย')

@section('content')
<!-- Hero Section -->
<section class="hero-section relative min-h-screen flex items-center justify-center overflow-hidden">
    <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
            <!-- Content -->
            <div class="text-slate-800">
                <h1 class="text-5xl lg:text-6xl font-bold mb-6 leading-tight">
                    ยินดีต้อนรับสู่<br>
                    <span class="text-primary">{{ \App\Helpers\SettingsHelper::get('site_name', config('app.name')) }}</span>
                </h1>
                <p class="text-xl text-slate-600 mb-8 leading-relaxed">
                    ระบบจัดการข้อมูลที่ทันสมัย ปลอดภัย และใช้งานง่าย 
                    พร้อมให้บริการคุณด้วยเทคโนโลยีที่ล้ำสมัย
                </p>
                <div class="flex flex-col sm:flex-row gap-4">
                    @auth
                        <a href="{{ route('admin.dashboard') }}" class="btn-primary btn-lg">
                            <i class="fas fa-tachometer-alt"></i>
                            เข้าสู่แดชบอร์ด
                        </a>
                    @else
                        <a href="/admin" class="btn-primary btn-lg">
                            <i class="fas fa-user-plus"></i>
                            เริ่มต้นใช้งาน
                        </a>
                        <a href="/admin" class="btn-outline btn-lg">
                            <i class="fas fa-sign-in-alt"></i>
                            เข้าสู่ระบบ
                        </a>
                    @endauth
                </div>
            </div>
            
            <!-- Image -->
            <div class="relative">
                <div class="card p-8">
                    <img src="https://placehold.co/500x400/1e40af/ffffff?text=Dashboard+Preview" 
                         class="w-full rounded-lg shadow-sm" alt="Dashboard Preview">
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Features Section -->
<section class="py-20 bg-slate-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-4xl lg:text-5xl font-bold text-slate-800 mb-6">คุณสมบัติเด่น</h2>
            <p class="text-xl text-slate-600 max-w-3xl mx-auto">
                ระบบที่ออกแบบมาเพื่อความสะดวกและประสิทธิภาพสูงสุด
            </p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <!-- Feature 1 -->
            <div class="card hover:-translate-y-2">
                <div class="card-body text-center">
                    <div class="w-16 h-16 bg-primary rounded-lg flex items-center justify-center text-white text-2xl mb-6 mx-auto">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-slate-800 mb-4">ความปลอดภัยสูง</h3>
                    <p class="text-slate-600 leading-relaxed">
                        ระบบรักษาความปลอดภัยระดับสูง พร้อมการเข้ารหัสข้อมูล 
                        และการตรวจสอบสิทธิ์ที่เข้มงวด
                    </p>
                </div>
            </div>
            
            <!-- Feature 2 -->
            <div class="card hover:-translate-y-2">
                <div class="card-body text-center">
                    <div class="w-16 h-16 bg-accent rounded-lg flex items-center justify-center text-white text-2xl mb-6 mx-auto">
                        <i class="fas fa-mobile-alt"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-slate-800 mb-4">รองรับทุกอุปกรณ์</h3>
                    <p class="text-slate-600 leading-relaxed">
                        ออกแบบให้ใช้งานได้บนทุกอุปกรณ์ ไม่ว่าจะเป็นคอมพิวเตอร์ 
                        แท็บเล็ต หรือสมาร์ทโฟน
                    </p>
                </div>
            </div>
            
            <!-- Feature 3 -->
            <div class="card hover:-translate-y-2">
                <div class="card-body text-center">
                    <div class="w-16 h-16 bg-secondary rounded-lg flex items-center justify-center text-white text-2xl mb-6 mx-auto">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-slate-800 mb-4">รายงานแบบเรียลไทม์</h3>
                    <p class="text-slate-600 leading-relaxed">
                        ดูข้อมูลและรายงานแบบเรียลไทม์ พร้อมกราฟและสถิติ 
                        ที่ช่วยในการตัดสินใจ
                    </p>
                </div>
            </div>
            
            <!-- Feature 4 -->
            <div class="card hover:-translate-y-2">
                <div class="card-body text-center">
                    <div class="w-16 h-16 bg-primary rounded-lg flex items-center justify-center text-white text-2xl mb-6 mx-auto">
                        <i class="fas fa-users-cog"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-slate-800 mb-4">จัดการผู้ใช้</h3>
                    <p class="text-slate-600 leading-relaxed">
                        ระบบจัดการผู้ใช้ที่ครบครัน พร้อมการกำหนดสิทธิ์ 
                        และการควบคุมการเข้าถึง
                    </p>
                </div>
            </div>
            
            <!-- Feature 5 -->
            <div class="card hover:-translate-y-2">
                <div class="card-body text-center">
                    <div class="w-16 h-16 bg-accent rounded-lg flex items-center justify-center text-white text-2xl mb-6 mx-auto">
                        <i class="fas fa-database"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-slate-800 mb-4">ฐานข้อมูลที่เสถียร</h3>
                    <p class="text-slate-600 leading-relaxed">
                        ใช้ฐานข้อมูล MySQL ที่เสถียรและรวดเร็ว 
                        พร้อมการสำรองข้อมูลอัตโนมัติ
                    </p>
                </div>
            </div>
            
            <!-- Feature 6 -->
            <div class="card hover:-translate-y-2">
                <div class="card-body text-center">
                    <div class="w-16 h-16 bg-secondary rounded-lg flex items-center justify-center text-white text-2xl mb-6 mx-auto">
                        <i class="fas fa-headset"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-slate-800 mb-4">บริการลูกค้า 24/7</h3>
                    <p class="text-slate-600 leading-relaxed">
                        ทีมสนับสนุนพร้อมให้บริการตลอด 24 ชั่วโมง 
                        เพื่อความมั่นใจในการใช้งาน
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Statistics Section -->
<section class="py-20 bg-slate-900 text-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
            <!-- Stat 1 -->
            <div class="text-center p-8 bg-gradient-primary rounded-2xl shadow-xl">
                <div class="w-16 h-16 bg-white/20 rounded-2xl flex items-center justify-center text-white text-3xl mb-6 mx-auto">
                    <i class="fas fa-users"></i>
                </div>
                <h3 class="text-4xl font-bold mb-2">{{ $totalUsers ?? '1,234' }}</h3>
                <p class="text-white/80">ผู้ใช้ที่ไว้วางใจ</p>
            </div>
            
            <!-- Stat 2 -->
            <div class="text-center p-8 bg-gradient-success rounded-2xl shadow-xl">
                <div class="w-16 h-16 bg-white/20 rounded-2xl flex items-center justify-center text-white text-3xl mb-6 mx-auto">
                    <i class="fas fa-chart-bar"></i>
                </div>
                <h3 class="text-4xl font-bold mb-2">{{ $totalData ?? '5,678' }}</h3>
                <p class="text-white/80">ข้อมูลที่จัดการ</p>
            </div>
            
            <!-- Stat 3 -->
            <div class="text-center p-8 bg-gradient-info rounded-2xl shadow-xl">
                <div class="w-16 h-16 bg-white/20 rounded-2xl flex items-center justify-center text-white text-3xl mb-6 mx-auto">
                    <i class="fas fa-clock"></i>
                </div>
                <h3 class="text-4xl font-bold mb-2">{{ $uptime ?? '99.9' }}%</h3>
                <p class="text-white/80">เวลาทำงาน</p>
            </div>
            
            <!-- Stat 4 -->
            <div class="text-center p-8 bg-gradient-warning rounded-2xl shadow-xl">
                <div class="w-16 h-16 bg-white/20 rounded-2xl flex items-center justify-center text-white text-3xl mb-6 mx-auto">
                    <i class="fas fa-star"></i>
                </div>
                <h3 class="text-4xl font-bold mb-2">{{ $rating ?? '4.8' }}</h3>
                <p class="text-white/80">คะแนนความพึงพอใจ</p>
            </div>
        </div>
    </div>
</section>

<!-- Testimonials Section -->
<section class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-4xl lg:text-5xl font-bold text-slate-800 mb-6">เสียงตอบรับจากลูกค้า</h2>
            <p class="text-xl text-slate-600 max-w-3xl mx-auto">
                ความพึงพอใจของลูกค้าคือสิ่งสำคัญที่สุด
            </p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <!-- Testimonial 1 -->
            <div class="bg-slate-50 rounded-2xl p-8 shadow-lg hover:shadow-xl transition-all duration-300">
                <div class="text-center">
                    <div class="w-20 h-20 bg-gradient-primary rounded-full flex items-center justify-center text-white text-2xl font-bold mx-auto mb-6">
                        ส
                    </div>
                    <h4 class="text-xl font-semibold text-slate-800 mb-2">คุณสมชาย ใจดี</h4>
                    <p class="text-slate-600 mb-4">CEO, บริษัท ABC</p>
                    <p class="text-slate-700 leading-relaxed mb-6">
                        "ระบบใช้งานง่ายมาก มีความเสถียรสูง และทีมสนับสนุนให้บริการดีเยี่ยม 
                        แนะนำให้ทุกคนลองใช้"
                    </p>
                    <div class="flex justify-center text-yellow-400 text-xl">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                    </div>
                </div>
            </div>
            
            <!-- Testimonial 2 -->
            <div class="bg-slate-50 rounded-2xl p-8 shadow-lg hover:shadow-xl transition-all duration-300">
                <div class="text-center">
                    <div class="w-20 h-20 bg-gradient-success rounded-full flex items-center justify-center text-white text-2xl font-bold mx-auto mb-6">
                        ส
                    </div>
                    <h4 class="text-xl font-semibold text-slate-800 mb-2">คุณสมหญิง รักดี</h4>
                    <p class="text-slate-600 mb-4">Manager, บริษัท XYZ</p>
                    <p class="text-slate-700 leading-relaxed mb-6">
                        "ช่วยประหยัดเวลาในการทำงานได้มาก รายงานที่ได้ชัดเจน 
                        และสามารถเข้าถึงข้อมูลได้ทุกที่ทุกเวลา"
                    </p>
                    <div class="flex justify-center text-yellow-400 text-xl">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                    </div>
                </div>
            </div>
            
            <!-- Testimonial 3 -->
            <div class="bg-slate-50 rounded-2xl p-8 shadow-lg hover:shadow-xl transition-all duration-300">
                <div class="text-center">
                    <div class="w-20 h-20 bg-gradient-info rounded-full flex items-center justify-center text-white text-2xl font-bold mx-auto mb-6">
                        ส
                    </div>
                    <h4 class="text-xl font-semibold text-slate-800 mb-2">คุณสมศักดิ์ เก่งดี</h4>
                    <p class="text-slate-600 mb-4">Director, บริษัท DEF</p>
                    <p class="text-slate-700 leading-relaxed mb-6">
                        "ระบบมีความปลอดภัยสูง ข้อมูลไม่รั่วไหล และการสำรองข้อมูล 
                        ทำให้เรามั่นใจในการใช้งาน"
                    </p>
                    <div class="flex justify-center text-yellow-400 text-xl">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-20 bg-gradient-primary text-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-4xl lg:text-5xl font-bold mb-6">พร้อมเริ่มต้นแล้วหรือยัง?</h2>
        <p class="text-xl text-white/90 mb-8 max-w-3xl mx-auto">
            เข้าร่วมกับผู้ใช้หลายพันคนที่ไว้วางใจระบบของเรา 
            และเริ่มต้นการจัดการข้อมูลของคุณวันนี้
        </p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            @auth
                <a href="{{ route('admin.dashboard') }}" class="bg-yellow-400 text-slate-900 px-8 py-4 rounded-xl font-semibold text-lg hover:bg-yellow-300 hover:shadow-xl hover:-translate-y-1 transition-all duration-300 flex items-center justify-center gap-3">
                    <i class="fas fa-tachometer-alt"></i>
                    เข้าสู่แดชบอร์ด
                </a>
            @else
                <a href="{{ route('register') }}" class="bg-yellow-400 text-slate-900 px-8 py-4 rounded-xl font-semibold text-lg hover:bg-yellow-300 hover:shadow-xl hover:-translate-y-1 transition-all duration-300 flex items-center justify-center gap-3">
                    <i class="fas fa-user-plus"></i>
                    สมัครสมาชิกฟรี
                </a>
                <a href="{{ route('contact') }}" class="border-2 border-white text-white px-8 py-4 rounded-xl font-semibold text-lg hover:bg-white hover:text-slate-900 hover:shadow-xl hover:-translate-y-1 transition-all duration-300 flex items-center justify-center gap-3">
                    <i class="fas fa-envelope"></i>
                    ติดต่อเรา
                </a>
            @endauth
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
// Animate numbers on scroll
function animateValue(element, start, end, duration) {
    let startTimestamp = null;
    const step = (timestamp) => {
        if (!startTimestamp) startTimestamp = timestamp;
        const progress = Math.min((timestamp - startTimestamp) / duration, 1);
        const current = Math.floor(progress * (end - start) + start);
        element.textContent = current.toLocaleString();
        if (progress < 1) {
            window.requestAnimationFrame(step);
        }
    };
    window.requestAnimationFrame(step);
}

// Intersection Observer for animations
const observerOptions = {
    threshold: 0.5,
    rootMargin: '0px 0px -100px 0px'
};

const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            const counters = entry.target.querySelectorAll('.text-4xl');
            counters.forEach(counter => {
                const target = parseInt(counter.textContent.replace(/[^\d]/g, ''));
                if (target > 0) {
                    animateValue(counter, 0, target, 2000);
                }
            });
            observer.unobserve(entry.target);
        }
    });
}, observerOptions);

// Observe statistics section
const statsSection = document.querySelector('.bg-slate-900');
if (statsSection) {
    observer.observe(statsSection);
}

// Smooth scrolling for anchor links
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        e.preventDefault();
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
            target.scrollIntoView({
                behavior: 'smooth'
            });
        }
    });
});
</script>
@endpush