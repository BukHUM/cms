@extends('layouts.frontend')

@section('title', 'เกี่ยวกับเรา')
@section('description', 'เรียนรู้เกี่ยวกับทีมพัฒนาและวิสัยทัศน์ของเรา')

@section('content')
<!-- Hero Section -->
<section class="frontend-hero">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <h1 class="display-4 fw-bold mb-4">เกี่ยวกับเรา</h1>
                <p class="lead mb-4">
                    เราคือทีมพัฒนาที่มีความเชี่ยวชาญในการสร้างระบบจัดการข้อมูล
                    ที่ทันสมัย ปลอดภัย และใช้งานง่าย
                </p>
            </div>
            <div class="col-lg-6 text-center">
                <img src="https://placehold.co/500x300/667eea/ffffff?text=About+Us" 
                     class="img-fluid rounded-3 shadow-lg" alt="About Us">
            </div>
        </div>
    </div>
</section>

<!-- Mission & Vision -->
<section class="py-5">
    <div class="container">
        <div class="row g-5">
            <div class="col-md-6">
                <div class="card h-100">
                    <div class="card-body text-center p-5">
                        <div class="feature-icon">
                            <i class="fas fa-bullseye"></i>
                        </div>
                        <h3 class="card-title">พันธกิจ</h3>
                        <p class="card-text">
                            พัฒนาระบบจัดการข้อมูลที่ตอบสนองความต้องการของลูกค้า
                            ด้วยเทคโนโลยีที่ทันสมัยและนวัตกรรมที่สร้างสรรค์
                        </p>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="card h-100">
                    <div class="card-body text-center p-5">
                        <div class="feature-icon">
                            <i class="fas fa-eye"></i>
                        </div>
                        <h3 class="card-title">วิสัยทัศน์</h3>
                        <p class="card-text">
                            เป็นผู้นำในการให้บริการระบบจัดการข้อมูลที่มีคุณภาพสูง
                            และเป็นที่ไว้วางใจของลูกค้าทุกกลุ่ม
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Team Section -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="row text-center mb-5">
            <div class="col-12">
                <h2 class="h1 mb-3">ทีมพัฒนา</h2>
                <p class="lead text-muted">ทีมผู้เชี่ยวชาญที่พร้อมให้บริการคุณ</p>
            </div>
        </div>
        
        <div class="row g-4">
            <div class="col-md-4">
                <div class="card h-100 text-center">
                    <div class="card-body p-4">
                        <img src="https://placehold.co/150" class="rounded-circle mb-3" alt="Team Member">
                        <h5 class="card-title">คุณสมชาย ใจดี</h5>
                        <p class="text-muted">Project Manager</p>
                        <p class="card-text">
                            ผู้จัดการโครงการที่มีประสบการณ์มากกว่า 10 ปี
                            ในด้านการพัฒนาระบบจัดการข้อมูล
                        </p>
                        <div class="social-links">
                            <a href="#" class="me-2"><i class="fab fa-linkedin-in"></i></a>
                            <a href="#" class="me-2"><i class="fab fa-twitter"></i></a>
                            <a href="#"><i class="fab fa-github"></i></a>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="card h-100 text-center">
                    <div class="card-body p-4">
                        <img src="https://placehold.co/150" class="rounded-circle mb-3" alt="Team Member">
                        <h5 class="card-title">คุณสมหญิง รักดี</h5>
                        <p class="text-muted">Lead Developer</p>
                        <p class="card-text">
                            นักพัฒนาหลักที่เชี่ยวชาญใน Laravel และ PHP
                            มีประสบการณ์ในการสร้างระบบขนาดใหญ่
                        </p>
                        <div class="social-links">
                            <a href="#" class="me-2"><i class="fab fa-linkedin-in"></i></a>
                            <a href="#" class="me-2"><i class="fab fa-twitter"></i></a>
                            <a href="#"><i class="fab fa-github"></i></a>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="card h-100 text-center">
                    <div class="card-body p-4">
                        <img src="https://placehold.co/150" class="rounded-circle mb-3" alt="Team Member">
                        <h5 class="card-title">คุณสมศักดิ์ เก่งดี</h5>
                        <p class="text-muted">UI/UX Designer</p>
                        <p class="card-text">
                            นักออกแบบที่สร้างประสบการณ์ผู้ใช้ที่ดี
                            และอินเทอร์เฟซที่สวยงามใช้งานง่าย
                        </p>
                        <div class="social-links">
                            <a href="#" class="me-2"><i class="fab fa-linkedin-in"></i></a>
                            <a href="#" class="me-2"><i class="fab fa-twitter"></i></a>
                            <a href="#"><i class="fab fa-dribbble"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Values Section -->
<section class="py-5">
    <div class="container">
        <div class="row text-center mb-5">
            <div class="col-12">
                <h2 class="h1 mb-3">ค่านิยมของเรา</h2>
                <p class="lead text-muted">หลักการที่เรายึดถือในการทำงาน</p>
            </div>
        </div>
        
        <div class="row g-4">
            <div class="col-md-3">
                <div class="text-center">
                    <div class="feature-icon">
                        <i class="fas fa-handshake"></i>
                    </div>
                    <h5>ความไว้วางใจ</h5>
                    <p class="text-muted">สร้างความไว้วางใจกับลูกค้าด้วยการให้บริการที่ดี</p>
                </div>
            </div>
            
            <div class="col-md-3">
                <div class="text-center">
                    <div class="feature-icon">
                        <i class="fas fa-lightbulb"></i>
                    </div>
                    <h5>นวัตกรรม</h5>
                    <p class="text-muted">พัฒนานวัตกรรมใหม่ๆ เพื่อตอบสนองความต้องการ</p>
                </div>
            </div>
            
            <div class="col-md-3">
                <div class="text-center">
                    <div class="feature-icon">
                        <i class="fas fa-star"></i>
                    </div>
                    <h5>คุณภาพ</h5>
                    <p class="text-muted">มุ่งมั่นให้บริการที่มีคุณภาพสูงสุด</p>
                </div>
            </div>
            
            <div class="col-md-3">
                <div class="text-center">
                    <div class="feature-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <h5>ทีมเวิร์ค</h5>
                    <p class="text-muted">ทำงานร่วมกันเป็นทีมเพื่อเป้าหมายเดียวกัน</p>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
