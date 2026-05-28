@extends('layouts.frontend')

@section('title', 'บริการของเรา')
@section('description', 'บริการที่เรามีให้กับลูกค้าทุกท่าน')

@section('content')
<!-- Hero Section -->
<section class="hero-section relative min-h-screen flex items-center justify-center overflow-hidden">
    <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
            <!-- Content -->
            <div class="text-slate-800">
                <h1 class="text-5xl lg:text-6xl font-bold mb-6 leading-tight">
                    บริการของเรา
                </h1>
                <p class="text-xl text-slate-600 mb-8 leading-relaxed">
                    บริการครบวงจรสำหรับการจัดการข้อมูล
                    ที่ตอบสนองความต้องการทุกด้าน
                </p>
            </div>
            
            <!-- Image -->
            <div class="relative">
                <div class="relative z-10 bg-white/10 backdrop-blur-lg rounded-3xl p-8 border border-white/20 shadow-2xl">
                    <img src="https://placehold.co/500x300/667eea/ffffff?text=Our+Services" 
                         class="w-full rounded-2xl shadow-xl" alt="Our Services">
                </div>
                <!-- Floating Elements -->
                <div class="absolute -top-4 -right-4 w-8 h-8 bg-yellow-400 rounded-full animate-bounce"></div>
                <div class="absolute -bottom-4 -left-4 w-6 h-6 bg-white/30 rounded-full animate-pulse"></div>
            </div>
        </div>
    </div>
</section>

<!-- Services Grid -->
<section class="py-20 bg-slate-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-4xl lg:text-5xl font-bold text-slate-800 mb-6">บริการของเรา</h2>
            <p class="text-xl text-slate-600 max-w-3xl mx-auto">
                บริการครบวงจรที่ตอบสนองความต้องการทุกด้าน
            </p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <!-- Service 1 -->
            <div class="bg-white rounded-2xl p-8 shadow-lg hover:shadow-xl transition-all duration-300 border border-slate-200 hover:-translate-y-2">
                <div class="w-16 h-16 bg-gradient-primary rounded-2xl flex items-center justify-center text-white text-2xl mb-6 mx-auto">
                    <i class="fas fa-database"></i>
                </div>
                <h3 class="text-xl font-semibold text-slate-800 mb-4 text-center">ระบบจัดการข้อมูล</h3>
                <p class="text-slate-600 text-center leading-relaxed mb-6">
                    จัดการข้อมูลอย่างเป็นระบบ ด้วยระบบฐานข้อมูลที่เสถียร
                    และการสำรองข้อมูลอัตโนมัติ
                </p>
                <ul class="space-y-2 text-slate-600">
                    <li class="flex items-center">
                        <i class="fas fa-check text-green-500 mr-3"></i>
                        ฐานข้อมูล MySQL
                    </li>
                    <li class="flex items-center">
                        <i class="fas fa-check text-green-500 mr-3"></i>
                        การสำรองข้อมูลอัตโนมัติ
                    </li>
                    <li class="flex items-center">
                        <i class="fas fa-check text-green-500 mr-3"></i>
                        การจัดเก็บข้อมูลแบบ Cloud
                    </li>
                </ul>
            </div>
            
            <!-- Service 2 -->
            <div class="bg-white rounded-2xl p-8 shadow-lg hover:shadow-xl transition-all duration-300 border border-slate-200 hover:-translate-y-2">
                <div class="w-16 h-16 bg-gradient-success rounded-2xl flex items-center justify-center text-white text-2xl mb-6 mx-auto">
                    <i class="fas fa-users-cog"></i>
                </div>
                <h3 class="text-xl font-semibold text-slate-800 mb-4 text-center">จัดการผู้ใช้</h3>
                <p class="text-slate-600 text-center leading-relaxed mb-6">
                    ระบบจัดการผู้ใช้ที่ครบครัน พร้อมการกำหนดสิทธิ์
                    และการควบคุมการเข้าถึงข้อมูล
                </p>
                <ul class="space-y-2 text-slate-600">
                    <li class="flex items-center">
                        <i class="fas fa-check text-green-500 mr-3"></i>
                        ระบบยืนยันตัวตน
                    </li>
                    <li class="flex items-center">
                        <i class="fas fa-check text-green-500 mr-3"></i>
                        การกำหนดสิทธิ์ผู้ใช้
                    </li>
                    <li class="flex items-center">
                        <i class="fas fa-check text-green-500 mr-3"></i>
                        การจัดการโปรไฟล์
                    </li>
                </ul>
            </div>
            
            <!-- Service 3 -->
            <div class="bg-white rounded-2xl p-8 shadow-lg hover:shadow-xl transition-all duration-300 border border-slate-200 hover:-translate-y-2">
                <div class="w-16 h-16 bg-gradient-info rounded-2xl flex items-center justify-center text-white text-2xl mb-6 mx-auto">
                    <i class="fas fa-chart-bar"></i>
                </div>
                <h3 class="text-xl font-semibold text-slate-800 mb-4 text-center">รายงานและสถิติ</h3>
                <p class="text-slate-600 text-center leading-relaxed mb-6">
                    สร้างรายงานและสถิติแบบเรียลไทม์ พร้อมกราฟ
                    และการวิเคราะห์ข้อมูลที่ละเอียด
                </p>
                <ul class="space-y-2 text-slate-600">
                    <li class="flex items-center">
                        <i class="fas fa-check text-green-500 mr-3"></i>
                        กราฟแบบเรียลไทม์
                    </li>
                    <li class="flex items-center">
                        <i class="fas fa-check text-green-500 mr-3"></i>
                        รายงานที่ปรับแต่งได้
                    </li>
                    <li class="flex items-center">
                        <i class="fas fa-check text-green-500 mr-3"></i>
                        การส่งออกข้อมูล
                    </li>
                </ul>
            </div>
            
            <!-- Service 4 -->
            <div class="bg-white rounded-2xl p-8 shadow-lg hover:shadow-xl transition-all duration-300 border border-slate-200 hover:-translate-y-2">
                <div class="w-16 h-16 bg-gradient-warning rounded-2xl flex items-center justify-center text-white text-2xl mb-6 mx-auto">
                    <i class="fas fa-mobile-alt"></i>
                </div>
                <h3 class="text-xl font-semibold text-slate-800 mb-4 text-center">รองรับทุกอุปกรณ์</h3>
                <p class="text-slate-600 text-center leading-relaxed mb-6">
                    ใช้งานได้บนทุกอุปกรณ์ ไม่ว่าจะเป็นคอมพิวเตอร์
                    แท็บเล็ต หรือสมาร์ทโฟน
                </p>
                <ul class="space-y-2 text-slate-600">
                    <li class="flex items-center">
                        <i class="fas fa-check text-green-500 mr-3"></i>
                        Responsive Design
                    </li>
                    <li class="flex items-center">
                        <i class="fas fa-check text-green-500 mr-3"></i>
                        Mobile App
                    </li>
                    <li class="flex items-center">
                        <i class="fas fa-check text-green-500 mr-3"></i>
                        Cross Platform
                    </li>
                </ul>
            </div>
            
            <!-- Service 5 -->
            <div class="bg-white rounded-2xl p-8 shadow-lg hover:shadow-xl transition-all duration-300 border border-slate-200 hover:-translate-y-2">
                <div class="w-16 h-16 bg-gradient-danger rounded-2xl flex items-center justify-center text-white text-2xl mb-6 mx-auto">
                    <i class="fas fa-shield-alt"></i>
                </div>
                <h3 class="text-xl font-semibold text-slate-800 mb-4 text-center">ความปลอดภัย</h3>
                <p class="text-slate-600 text-center leading-relaxed mb-6">
                    ระบบรักษาความปลอดภัยระดับสูง พร้อมการเข้ารหัส
                    และการตรวจสอบสิทธิ์ที่เข้มงวด
                </p>
                <ul class="space-y-2 text-slate-600">
                    <li class="flex items-center">
                        <i class="fas fa-check text-green-500 mr-3"></i>
                        การเข้ารหัสข้อมูล
                    </li>
                    <li class="flex items-center">
                        <i class="fas fa-check text-green-500 mr-3"></i>
                        SSL Certificate
                    </li>
                    <li class="flex items-center">
                        <i class="fas fa-check text-green-500 mr-3"></i>
                        การตรวจสอบสิทธิ์
                    </li>
                </ul>
            </div>
            
            <!-- Service 6 -->
            <div class="bg-white rounded-2xl p-8 shadow-lg hover:shadow-xl transition-all duration-300 border border-slate-200 hover:-translate-y-2">
                <div class="w-16 h-16 bg-gradient-secondary rounded-2xl flex items-center justify-center text-white text-2xl mb-6 mx-auto">
                    <i class="fas fa-headset"></i>
                </div>
                <h3 class="text-xl font-semibold text-slate-800 mb-4 text-center">สนับสนุนลูกค้า</h3>
                <p class="text-slate-600 text-center leading-relaxed mb-6">
                    ทีมสนับสนุนลูกค้าที่พร้อมให้บริการตลอด 24 ชั่วโมง
                    เพื่อความมั่นใจในการใช้งาน
                </p>
                <ul class="space-y-2 text-slate-600">
                    <li class="flex items-center">
                        <i class="fas fa-check text-green-500 mr-3"></i>
                        บริการ 24/7
                    </li>
                    <li class="flex items-center">
                        <i class="fas fa-check text-green-500 mr-3"></i>
                        การฝึกอบรม
                    </li>
                    <li class="flex items-center">
                        <i class="fas fa-check text-green-500 mr-3"></i>
                        คู่มือการใช้งาน
                    </li>
                </ul>
            </div>
        </div>
    </div>
</section>

<!-- Pricing Section -->
<section class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-4xl lg:text-5xl font-bold text-slate-800 mb-6">แพ็คเกจราคา</h2>
            <p class="text-xl text-slate-600 max-w-3xl mx-auto">
                เลือกแพ็คเกจที่เหมาะสมกับความต้องการของคุณ
            </p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- Package 1 -->
            <div class="bg-white rounded-2xl shadow-lg border border-slate-200 hover:shadow-xl transition-all duration-300 hover:-translate-y-2">
                <div class="bg-gradient-primary text-white text-center p-8 rounded-t-2xl">
                    <h4 class="text-xl font-semibold mb-4">แพ็คเกจเริ่มต้น</h4>
                    <div class="text-5xl font-bold mb-2">฿999</div>
                    <p class="text-white/80">/เดือน</p>
                </div>
                <div class="p-8">
                    <ul class="space-y-4 mb-8">
                        <li class="flex items-center">
                            <i class="fas fa-check text-green-500 mr-3"></i>
                            ผู้ใช้สูงสุด 10 คน
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-check text-green-500 mr-3"></i>
                            พื้นที่เก็บข้อมูล 1GB
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-check text-green-500 mr-3"></i>
                            รายงานพื้นฐาน
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-check text-green-500 mr-3"></i>
                            สนับสนุนอีเมล
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-check text-green-500 mr-3"></i>
                            การสำรองข้อมูลรายวัน
                        </li>
                    </ul>
                    <a href="{{ route('register') }}" class="w-full bg-slate-100 text-slate-800 px-6 py-3 rounded-xl font-semibold hover:bg-slate-200 transition-all duration-300 flex items-center justify-center gap-2">
                        <i class="fas fa-arrow-right"></i>
                        เลือกแพ็คเกจนี้
                    </a>
                </div>
            </div>
            
            <!-- Package 2 -->
            <div class="bg-white rounded-2xl shadow-xl border-2 border-primary hover:shadow-2xl transition-all duration-300 hover:-translate-y-2 relative">
                <div class="absolute -top-4 left-1/2 transform -translate-x-1/2">
                    <span class="bg-yellow-400 text-slate-900 px-4 py-2 rounded-full text-sm font-semibold">แนะนำ</span>
                </div>
                <div class="bg-gradient-primary text-white text-center p-8 rounded-t-2xl">
                    <h4 class="text-xl font-semibold mb-4">แพ็คเกจมาตรฐาน</h4>
                    <div class="text-5xl font-bold mb-2">฿2,999</div>
                    <p class="text-white/80">/เดือน</p>
                </div>
                <div class="p-8">
                    <ul class="space-y-4 mb-8">
                        <li class="flex items-center">
                            <i class="fas fa-check text-green-500 mr-3"></i>
                            ผู้ใช้สูงสุด 50 คน
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-check text-green-500 mr-3"></i>
                            พื้นที่เก็บข้อมูล 10GB
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-check text-green-500 mr-3"></i>
                            รายงานขั้นสูง
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-check text-green-500 mr-3"></i>
                            สนับสนุนโทรศัพท์
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-check text-green-500 mr-3"></i>
                            การสำรองข้อมูลรายชั่วโมง
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-check text-green-500 mr-3"></i>
                            API Access
                        </li>
                    </ul>
                    <a href="{{ route('register') }}" class="w-full bg-gradient-primary text-white px-6 py-3 rounded-xl font-semibold hover:shadow-lg transition-all duration-300 flex items-center justify-center gap-2">
                        <i class="fas fa-star"></i>
                        เลือกแพ็คเกจนี้
                    </a>
                </div>
            </div>
            
            <!-- Package 3 -->
            <div class="bg-white rounded-2xl shadow-lg border border-slate-200 hover:shadow-xl transition-all duration-300 hover:-translate-y-2">
                <div class="bg-gradient-primary text-white text-center p-8 rounded-t-2xl">
                    <h4 class="text-xl font-semibold mb-4">แพ็คเกจองค์กร</h4>
                    <div class="text-5xl font-bold mb-2">฿9,999</div>
                    <p class="text-white/80">/เดือน</p>
                </div>
                <div class="p-8">
                    <ul class="space-y-4 mb-8">
                        <li class="flex items-center">
                            <i class="fas fa-check text-green-500 mr-3"></i>
                            ผู้ใช้ไม่จำกัด
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-check text-green-500 mr-3"></i>
                            พื้นที่เก็บข้อมูล 100GB
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-check text-green-500 mr-3"></i>
                            รายงานแบบกำหนดเอง
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-check text-green-500 mr-3"></i>
                            สนับสนุน 24/7
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-check text-green-500 mr-3"></i>
                            การสำรองข้อมูลแบบเรียลไทม์
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-check text-green-500 mr-3"></i>
                            API ไม่จำกัด
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-check text-green-500 mr-3"></i>
                            การฝึกอบรมฟรี
                        </li>
                    </ul>
                    <a href="{{ route('contact') }}" class="w-full bg-slate-100 text-slate-800 px-6 py-3 rounded-xl font-semibold hover:bg-slate-200 transition-all duration-300 flex items-center justify-center gap-2">
                        <i class="fas fa-envelope"></i>
                        ติดต่อสอบถาม
                    </a>
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
            เลือกแพ็คเกจที่เหมาะสมกับความต้องการของคุณ
            และเริ่มต้นการจัดการข้อมูลวันนี้
        </p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="{{ route('register') }}" class="bg-yellow-400 text-slate-900 px-8 py-4 rounded-xl font-semibold text-lg hover:bg-yellow-300 hover:shadow-xl hover:-translate-y-1 transition-all duration-300 flex items-center justify-center gap-3">
                <i class="fas fa-user-plus"></i>
                สมัครสมาชิกฟรี
            </a>
            <a href="{{ route('contact') }}" class="border-2 border-white text-white px-8 py-4 rounded-xl font-semibold text-lg hover:bg-white hover:text-slate-900 hover:shadow-xl hover:-translate-y-1 transition-all duration-300 flex items-center justify-center gap-3">
                <i class="fas fa-envelope"></i>
                ติดต่อสอบถาม
            </a>
        </div>
    </div>
</section>
@endsection
