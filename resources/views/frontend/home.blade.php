@extends('layouts.frontend')

@section('title', 'หน้าแรก')
@section('description', 'ยินดีต้อนรับสู่ระบบจัดการข้อมูลที่ทันสมัยและใช้งานง่าย')

@section('content')
<!-- Success/Error Messages -->
@if(session('success'))
    <div class="container mt-3">
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    </div>
@endif

@if(session('error'))
    <div class="container mt-3">
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    </div>
@endif

<!-- Hero Section -->
<section class="frontend-hero">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <h1 class="display-4 fw-bold mb-4">
                    ยินดีต้อนรับสู่<br>
                    <span class="text-warning">{{ \App\Helpers\SettingsHelper::get('site_name', config('app.name')) }}</span>
                </h1>
                <p class="lead mb-4">
                    ระบบจัดการข้อมูลที่ทันสมัย ปลอดภัย และใช้งานง่าย 
                    พร้อมให้บริการคุณด้วยเทคโนโลยีที่ล้ำสมัย
                </p>
                <div class="d-flex gap-3">
                    @auth
                        <a href="{{ route('admin.dashboard') }}" class="frontend-btn-primary btn-lg">
                            <i class="fas fa-tachometer-alt"></i>
                            เข้าสู่แดชบอร์ด
                        </a>
                    @else
                        <a href="/admin" class="frontend-btn-primary btn-lg">
                            <i class="fas fa-user-plus"></i>
                            เริ่มต้นใช้งาน
                        </a>
                        <a href="/admin" class="frontend-btn-secondary btn-lg">
                            <i class="fas fa-sign-in-alt"></i>
                            เข้าสู่ระบบ
                        </a>
                    @endauth
                </div>
            </div>
            <div class="col-lg-6 text-center">
                <img src="https://placehold.co/500x400/667eea/ffffff?text=Dashboard+Preview" 
                     class="img-fluid rounded-3 shadow-lg" alt="Dashboard Preview">
            </div>
        </div>
    </div>
</section>

<!-- Features Section -->
<section class="frontend-section">
    <div class="container">
        <div class="row text-center mb-5">
            <div class="col-12">
                <h2 class="h1 mb-3">คุณสมบัติเด่น</h2>
                <p class="lead text-muted">ระบบที่ออกแบบมาเพื่อความสะดวกและประสิทธิภาพสูงสุด</p>
            </div>
        </div>
        
        <div class="row g-4">
            <div class="col-md-4">
                <div class="card h-100 text-center p-4 frontend-card">
                    <div class="frontend-icon">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <h5 class="card-title">ความปลอดภัยสูง</h5>
                    <p class="card-text">
                        ระบบรักษาความปลอดภัยระดับสูง พร้อมการเข้ารหัสข้อมูล 
                        และการตรวจสอบสิทธิ์ที่เข้มงวด
                    </p>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="card h-100 text-center p-4 frontend-card">
                    <div class="frontend-icon">
                        <i class="fas fa-mobile-alt"></i>
                    </div>
                    <h5 class="card-title">รองรับทุกอุปกรณ์</h5>
                    <p class="card-text">
                        ออกแบบให้ใช้งานได้บนทุกอุปกรณ์ ไม่ว่าจะเป็นคอมพิวเตอร์ 
                        แท็บเล็ต หรือสมาร์ทโฟน
                    </p>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="card h-100 text-center p-4 frontend-card">
                    <div class="frontend-icon">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <h5 class="card-title">รายงานแบบเรียลไทม์</h5>
                    <p class="card-text">
                        ดูข้อมูลและรายงานแบบเรียลไทม์ พร้อมกราฟและสถิติ 
                        ที่ช่วยในการตัดสินใจ
                    </p>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="card h-100 text-center p-4 frontend-card">
                    <div class="frontend-icon">
                        <i class="fas fa-users-cog"></i>
                    </div>
                    <h5 class="card-title">จัดการผู้ใช้</h5>
                    <p class="card-text">
                        ระบบจัดการผู้ใช้ที่ครบครัน พร้อมการกำหนดสิทธิ์ 
                        และการควบคุมการเข้าถึง
                    </p>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="card h-100 text-center p-4 frontend-card">
                    <div class="frontend-icon">
                        <i class="fas fa-database"></i>
                    </div>
                    <h5 class="card-title">ฐานข้อมูลที่เสถียร</h5>
                    <p class="card-text">
                        ใช้ฐานข้อมูล MySQL ที่เสถียรและรวดเร็ว 
                        พร้อมการสำรองข้อมูลอัตโนมัติ
                    </p>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="card h-100 text-center p-4 frontend-card">
                    <div class="frontend-icon">
                        <i class="fas fa-headset"></i>
                    </div>
                    <h5 class="card-title">บริการลูกค้า 24/7</h5>
                    <p class="card-text">
                        ทีมสนับสนุนพร้อมให้บริการตลอด 24 ชั่วโมง 
                        เพื่อความมั่นใจในการใช้งาน
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Statistics Section -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="row text-center">
            <div class="col-md-3 mb-4">
                <div class="card border-0 bg-primary text-white">
                    <div class="card-body">
                        <i class="fas fa-users fa-3x mb-3"></i>
                        <h3 class="display-4 fw-bold">{{ $totalUsers ?? '1,234' }}</h3>
                        <p class="mb-0">ผู้ใช้ที่ไว้วางใจ</p>
                    </div>
                </div>
            </div>
            
            <div class="col-md-3 mb-4">
                <div class="card border-0 bg-success text-white">
                    <div class="card-body">
                        <i class="fas fa-chart-bar fa-3x mb-3"></i>
                        <h3 class="display-4 fw-bold">{{ $totalData ?? '5,678' }}</h3>
                        <p class="mb-0">ข้อมูลที่จัดการ</p>
                    </div>
                </div>
            </div>
            
            <div class="col-md-3 mb-4">
                <div class="card border-0 bg-info text-white">
                    <div class="card-body">
                        <i class="fas fa-clock fa-3x mb-3"></i>
                        <h3 class="display-4 fw-bold">{{ $uptime ?? '99.9' }}%</h3>
                        <p class="mb-0">เวลาทำงาน</p>
                    </div>
                </div>
            </div>
            
            <div class="col-md-3 mb-4">
                <div class="card border-0 bg-warning text-white">
                    <div class="card-body">
                        <i class="fas fa-star fa-3x mb-3"></i>
                        <h3 class="display-4 fw-bold">{{ $rating ?? '4.8' }}</h3>
                        <p class="mb-0">คะแนนความพึงพอใจ</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Testimonials Section -->
<section class="py-5">
    <div class="container">
        <div class="row text-center mb-5">
            <div class="col-12">
                <h2 class="h1 mb-3">เสียงตอบรับจากลูกค้า</h2>
                <p class="lead text-muted">ความพึงพอใจของลูกค้าคือสิ่งสำคัญที่สุด</p>
            </div>
        </div>
        
        <div class="row g-4">
            <div class="col-md-4">
                <div class="card h-100">
                    <div class="card-body text-center">
                        <img src="https://placehold.co/80" class="rounded-circle mb-3" alt="Customer">
                        <h5 class="card-title">คุณสมชาย ใจดี</h5>
                        <p class="text-muted">CEO, บริษัท ABC</p>
                        <p class="card-text">
                            "ระบบใช้งานง่ายมาก มีความเสถียรสูง และทีมสนับสนุนให้บริการดีเยี่ยม 
                            แนะนำให้ทุกคนลองใช้"
                        </p>
                        <div class="text-warning">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="card h-100">
                    <div class="card-body text-center">
                        <img src="https://placehold.co/80" class="rounded-circle mb-3" alt="Customer">
                        <h5 class="card-title">คุณสมหญิง รักดี</h5>
                        <p class="text-muted">Manager, บริษัท XYZ</p>
                        <p class="card-text">
                            "ช่วยประหยัดเวลาในการทำงานได้มาก รายงานที่ได้ชัดเจน 
                            และสามารถเข้าถึงข้อมูลได้ทุกที่ทุกเวลา"
                        </p>
                        <div class="text-warning">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="card h-100">
                    <div class="card-body text-center">
                        <img src="https://placehold.co/80" class="rounded-circle mb-3" alt="Customer">
                        <h5 class="card-title">คุณสมศักดิ์ เก่งดี</h5>
                        <p class="text-muted">Director, บริษัท DEF</p>
                        <p class="card-text">
                            "ระบบมีความปลอดภัยสูง ข้อมูลไม่รั่วไหล และการสำรองข้อมูล 
                            ทำให้เรามั่นใจในการใช้งาน"
                        </p>
                        <div class="text-warning">
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
    </div>
</section>

<!-- CTA Section -->
<section class="py-5 bg-primary text-white">
    <div class="container text-center">
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <h2 class="h1 mb-4">พร้อมเริ่มต้นแล้วหรือยัง?</h2>
                <p class="lead mb-4">
                    เข้าร่วมกับผู้ใช้หลายพันคนที่ไว้วางใจระบบของเรา 
                    และเริ่มต้นการจัดการข้อมูลของคุณวันนี้
                </p>
                @auth
                    <a href="{{ route('dashboard') }}" class="btn btn-warning btn-lg me-3">
                        <i class="fas fa-tachometer-alt me-2"></i>
                        เข้าสู่แดชบอร์ด
                    </a>
                @else
                    <a href="{{ route('register') }}" class="btn btn-warning btn-lg me-3">
                        <i class="fas fa-user-plus me-2"></i>
                        สมัครสมาชิกฟรี
                    </a>
                    <a href="{{ route('contact') }}" class="btn btn-outline-light btn-lg">
                        <i class="fas fa-envelope me-2"></i>
                        ติดต่อเรา
                    </a>
                @endauth
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
// Smooth scrolling for anchor links
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        e.preventDefault();
        document.querySelector(this.getAttribute('href')).scrollIntoView({
            behavior: 'smooth'
        });
    });
});

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
            const counters = entry.target.querySelectorAll('.display-4');
            counters.forEach(counter => {
                const target = parseInt(counter.textContent.replace(/[^\d]/g, ''));
                animateValue(counter, 0, target, 2000);
            });
            observer.unobserve(entry.target);
        }
    });
}, observerOptions);

// Observe statistics section
const statsSection = document.querySelector('.bg-light');
if (statsSection) {
    observer.observe(statsSection);
}
</script>
@endpush
