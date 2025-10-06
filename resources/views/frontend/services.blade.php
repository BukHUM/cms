@extends('layouts.frontend')

@section('title', 'บริการของเรา')
@section('description', 'บริการที่เรามีให้กับลูกค้าทุกท่าน')

@section('content')
<!-- Hero Section -->
<section class="frontend-hero">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <h1 class="display-4 fw-bold mb-4">บริการของเรา</h1>
                <p class="lead mb-4">
                    บริการครบวงจรสำหรับการจัดการข้อมูล
                    ที่ตอบสนองความต้องการทุกด้าน
                </p>
            </div>
            <div class="col-lg-6 text-center">
                <img src="https://placehold.co/500x300/667eea/ffffff?text=Our+Services" 
                     class="img-fluid rounded-3 shadow-lg" alt="Our Services">
            </div>
        </div>
    </div>
</section>

<!-- Services Grid -->
<section class="py-5">
    <div class="container">
        <div class="row g-4">
            <div class="col-md-6 col-lg-4">
                <div class="card h-100 text-center">
                    <div class="card-body p-4">
                        <div class="feature-icon">
                            <i class="fas fa-database"></i>
                        </div>
                        <h5 class="card-title">ระบบจัดการข้อมูล</h5>
                        <p class="card-text">
                            จัดการข้อมูลอย่างเป็นระบบ ด้วยระบบฐานข้อมูลที่เสถียร
                            และการสำรองข้อมูลอัตโนมัติ
                        </p>
                        <ul class="list-unstyled text-start">
                            <li><i class="fas fa-check text-success me-2"></i>ฐานข้อมูล MySQL</li>
                            <li><i class="fas fa-check text-success me-2"></i>การสำรองข้อมูลอัตโนมัติ</li>
                            <li><i class="fas fa-check text-success me-2"></i>การจัดเก็บข้อมูลแบบ Cloud</li>
                        </ul>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6 col-lg-4">
                <div class="card h-100 text-center">
                    <div class="card-body p-4">
                        <div class="feature-icon">
                            <i class="fas fa-users-cog"></i>
                        </div>
                        <h5 class="card-title">จัดการผู้ใช้</h5>
                        <p class="card-text">
                            ระบบจัดการผู้ใช้ที่ครบครัน พร้อมการกำหนดสิทธิ์
                            และการควบคุมการเข้าถึงข้อมูล
                        </p>
                        <ul class="list-unstyled text-start">
                            <li><i class="fas fa-check text-success me-2"></i>ระบบยืนยันตัวตน</li>
                            <li><i class="fas fa-check text-success me-2"></i>การกำหนดสิทธิ์ผู้ใช้</li>
                            <li><i class="fas fa-check text-success me-2"></i>การจัดการโปรไฟล์</li>
                        </ul>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6 col-lg-4">
                <div class="card h-100 text-center">
                    <div class="card-body p-4">
                        <div class="feature-icon">
                            <i class="fas fa-chart-bar"></i>
                        </div>
                        <h5 class="card-title">รายงานและสถิติ</h5>
                        <p class="card-text">
                            สร้างรายงานและสถิติแบบเรียลไทม์ พร้อมกราฟ
                            และการวิเคราะห์ข้อมูลที่ละเอียด
                        </p>
                        <ul class="list-unstyled text-start">
                            <li><i class="fas fa-check text-success me-2"></i>กราฟแบบเรียลไทม์</li>
                            <li><i class="fas fa-check text-success me-2"></i>รายงานที่ปรับแต่งได้</li>
                            <li><i class="fas fa-check text-success me-2"></i>การส่งออกข้อมูล</li>
                        </ul>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6 col-lg-4">
                <div class="card h-100 text-center">
                    <div class="card-body p-4">
                        <div class="feature-icon">
                            <i class="fas fa-mobile-alt"></i>
                        </div>
                        <h5 class="card-title">รองรับทุกอุปกรณ์</h5>
                        <p class="card-text">
                            ใช้งานได้บนทุกอุปกรณ์ ไม่ว่าจะเป็นคอมพิวเตอร์
                            แท็บเล็ต หรือสมาร์ทโฟน
                        </p>
                        <ul class="list-unstyled text-start">
                            <li><i class="fas fa-check text-success me-2"></i>Responsive Design</li>
                            <li><i class="fas fa-check text-success me-2"></i>Mobile App</li>
                            <li><i class="fas fa-check text-success me-2"></i>Cross Platform</li>
                        </ul>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6 col-lg-4">
                <div class="card h-100 text-center">
                    <div class="card-body p-4">
                        <div class="feature-icon">
                            <i class="fas fa-shield-alt"></i>
                        </div>
                        <h5 class="card-title">ความปลอดภัย</h5>
                        <p class="card-text">
                            ระบบรักษาความปลอดภัยระดับสูง พร้อมการเข้ารหัส
                            และการตรวจสอบสิทธิ์ที่เข้มงวด
                        </p>
                        <ul class="list-unstyled text-start">
                            <li><i class="fas fa-check text-success me-2"></i>การเข้ารหัสข้อมูล</li>
                            <li><i class="fas fa-check text-success me-2"></i>SSL Certificate</li>
                            <li><i class="fas fa-check text-success me-2"></i>การตรวจสอบสิทธิ์</li>
                        </ul>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6 col-lg-4">
                <div class="card h-100 text-center">
                    <div class="card-body p-4">
                        <div class="feature-icon">
                            <i class="fas fa-headset"></i>
                        </div>
                        <h5 class="card-title">สนับสนุนลูกค้า</h5>
                        <p class="card-text">
                            ทีมสนับสนุนลูกค้าที่พร้อมให้บริการตลอด 24 ชั่วโมง
                            เพื่อความมั่นใจในการใช้งาน
                        </p>
                        <ul class="list-unstyled text-start">
                            <li><i class="fas fa-check text-success me-2"></i>บริการ 24/7</li>
                            <li><i class="fas fa-check text-success me-2"></i>การฝึกอบรม</li>
                            <li><i class="fas fa-check text-success me-2"></i>คู่มือการใช้งาน</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Pricing Section -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="row text-center mb-5">
            <div class="col-12">
                <h2 class="h1 mb-3">แพ็คเกจราคา</h2>
                <p class="lead text-muted">เลือกแพ็คเกจที่เหมาะสมกับความต้องการของคุณ</p>
            </div>
        </div>
        
        <div class="row g-4">
            <div class="col-md-4">
                <div class="card h-100">
                    <div class="card-header bg-primary text-white text-center">
                        <h4 class="mb-0">แพ็คเกจเริ่มต้น</h4>
                        <div class="display-4 fw-bold mt-3">฿999</div>
                        <small>/เดือน</small>
                    </div>
                    <div class="card-body p-4">
                        <ul class="list-unstyled">
                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i>ผู้ใช้สูงสุด 10 คน</li>
                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i>พื้นที่เก็บข้อมูล 1GB</li>
                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i>รายงานพื้นฐาน</li>
                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i>สนับสนุนอีเมล</li>
                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i>การสำรองข้อมูลรายวัน</li>
                        </ul>
                        <div class="d-grid">
                            <a href="{{ route('register') }}" class="btn btn-outline-primary">เลือกแพ็คเกจนี้</a>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="card h-100 border-primary">
                    <div class="card-header bg-primary text-white text-center position-relative">
                        <span class="badge bg-warning position-absolute top-0 start-50 translate-middle">แนะนำ</span>
                        <h4 class="mb-0">แพ็คเกจมาตรฐาน</h4>
                        <div class="display-4 fw-bold mt-3">฿2,999</div>
                        <small>/เดือน</small>
                    </div>
                    <div class="card-body p-4">
                        <ul class="list-unstyled">
                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i>ผู้ใช้สูงสุด 50 คน</li>
                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i>พื้นที่เก็บข้อมูล 10GB</li>
                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i>รายงานขั้นสูง</li>
                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i>สนับสนุนโทรศัพท์</li>
                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i>การสำรองข้อมูลรายชั่วโมง</li>
                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i>API Access</li>
                        </ul>
                        <div class="d-grid">
                            <a href="{{ route('register') }}" class="btn btn-primary">เลือกแพ็คเกจนี้</a>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="card h-100">
                    <div class="card-header bg-primary text-white text-center">
                        <h4 class="mb-0">แพ็คเกจองค์กร</h4>
                        <div class="display-4 fw-bold mt-3">฿9,999</div>
                        <small>/เดือน</small>
                    </div>
                    <div class="card-body p-4">
                        <ul class="list-unstyled">
                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i>ผู้ใช้ไม่จำกัด</li>
                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i>พื้นที่เก็บข้อมูล 100GB</li>
                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i>รายงานแบบกำหนดเอง</li>
                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i>สนับสนุน 24/7</li>
                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i>การสำรองข้อมูลแบบเรียลไทม์</li>
                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i>API ไม่จำกัด</li>
                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i>การฝึกอบรมฟรี</li>
                        </ul>
                        <div class="d-grid">
                            <a href="{{ route('contact') }}" class="btn btn-outline-primary">ติดต่อสอบถาม</a>
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
                    เลือกแพ็คเกจที่เหมาะสมกับความต้องการของคุณ
                    และเริ่มต้นการจัดการข้อมูลวันนี้
                </p>
                <div class="d-flex gap-3 justify-content-center">
                    <a href="{{ route('register') }}" class="btn btn-warning btn-lg">
                        <i class="fas fa-user-plus me-2"></i>
                        สมัครสมาชิกฟรี
                    </a>
                    <a href="{{ route('contact') }}" class="btn btn-outline-light btn-lg">
                        <i class="fas fa-envelope me-2"></i>
                        ติดต่อสอบถาม
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
