@extends('layouts.frontend')

@section('title', 'เกี่ยวกับเรา')
@section('description', 'เรียนรู้เกี่ยวกับทีมพัฒนาและวิสัยทัศน์ของเรา')

@section('content')
<!-- Hero Section -->
<section class="hero-section relative min-h-screen flex items-center justify-center overflow-hidden">
    <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
            <!-- Content -->
            <div class="text-slate-800">
                <h1 class="text-5xl lg:text-6xl font-bold mb-6 leading-tight">
                    เกี่ยวกับเรา
                </h1>
                <p class="text-xl text-slate-600 mb-8 leading-relaxed">
                    เราคือทีมพัฒนาที่มีความเชี่ยวชาญในการสร้างระบบจัดการข้อมูล
                    ที่ทันสมัย ปลอดภัย และใช้งานง่าย
                </p>
            </div>
            
            <!-- Image -->
            <div class="relative">
                <div class="relative z-10 bg-white/10 backdrop-blur-lg rounded-3xl p-8 border border-white/20 shadow-2xl">
                    <img src="https://placehold.co/500x300/667eea/ffffff?text=About+Us" 
                         class="w-full rounded-2xl shadow-xl" alt="About Us">
                </div>
                <!-- Floating Elements -->
                <div class="absolute -top-4 -right-4 w-8 h-8 bg-yellow-400 rounded-full animate-bounce"></div>
                <div class="absolute -bottom-4 -left-4 w-6 h-6 bg-white/30 rounded-full animate-pulse"></div>
            </div>
        </div>
    </div>
</section>

<!-- Mission & Vision -->
<section class="py-20 bg-slate-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-12">
            <!-- Mission -->
            <div class="bg-white rounded-2xl p-8 shadow-lg hover:shadow-xl transition-all duration-300 border border-slate-200 hover:-translate-y-2">
                <div class="text-center">
                    <div class="w-20 h-20 bg-gradient-primary rounded-2xl flex items-center justify-center text-white text-3xl mb-6 mx-auto">
                        <i class="fas fa-bullseye"></i>
                    </div>
                    <h3 class="text-2xl font-semibold text-slate-800 mb-6">พันธกิจ</h3>
                    <p class="text-slate-600 leading-relaxed">
                        พัฒนาระบบจัดการข้อมูลที่ตอบสนองความต้องการของลูกค้า
                        ด้วยเทคโนโลยีที่ทันสมัยและนวัตกรรมที่สร้างสรรค์
                    </p>
                </div>
            </div>
            
            <!-- Vision -->
            <div class="bg-white rounded-2xl p-8 shadow-lg hover:shadow-xl transition-all duration-300 border border-slate-200 hover:-translate-y-2">
                <div class="text-center">
                    <div class="w-20 h-20 bg-gradient-info rounded-2xl flex items-center justify-center text-white text-3xl mb-6 mx-auto">
                        <i class="fas fa-eye"></i>
                    </div>
                    <h3 class="text-2xl font-semibold text-slate-800 mb-6">วิสัยทัศน์</h3>
                    <p class="text-slate-600 leading-relaxed">
                        เป็นผู้นำในการให้บริการระบบจัดการข้อมูลที่มีคุณภาพสูง
                        และเป็นที่ไว้วางใจของลูกค้าทุกกลุ่ม
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Team Section -->
<section class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-4xl lg:text-5xl font-bold text-slate-800 mb-6">ทีมพัฒนา</h2>
            <p class="text-xl text-slate-600 max-w-3xl mx-auto">
                ทีมผู้เชี่ยวชาญที่พร้อมให้บริการคุณ
            </p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- Team Member 1 -->
            <div class="bg-white rounded-2xl p-8 shadow-lg hover:shadow-xl transition-all duration-300 border border-slate-200 hover:-translate-y-2 text-center">
                <img src="https://placehold.co/150" class="w-24 h-24 rounded-full mx-auto mb-6 shadow-lg" alt="Team Member">
                <h5 class="text-xl font-semibold text-slate-800 mb-2">คุณสมชาย ใจดี</h5>
                <p class="text-slate-600 mb-4">Project Manager</p>
                <p class="text-slate-600 leading-relaxed mb-6">
                    ผู้จัดการโครงการที่มีประสบการณ์มากกว่า 10 ปี
                    ในด้านการพัฒนาระบบจัดการข้อมูล
                </p>
                <div class="flex justify-center space-x-4">
                    <a href="#" class="w-10 h-10 bg-blue-600 text-white rounded-full flex items-center justify-center hover:bg-blue-700 transition-colors">
                        <i class="fab fa-linkedin-in"></i>
                    </a>
                    <a href="#" class="w-10 h-10 bg-sky-500 text-white rounded-full flex items-center justify-center hover:bg-sky-600 transition-colors">
                        <i class="fab fa-twitter"></i>
                    </a>
                    <a href="#" class="w-10 h-10 bg-slate-800 text-white rounded-full flex items-center justify-center hover:bg-slate-900 transition-colors">
                        <i class="fab fa-github"></i>
                    </a>
                </div>
            </div>
            
            <!-- Team Member 2 -->
            <div class="bg-white rounded-2xl p-8 shadow-lg hover:shadow-xl transition-all duration-300 border border-slate-200 hover:-translate-y-2 text-center">
                <img src="https://placehold.co/150" class="w-24 h-24 rounded-full mx-auto mb-6 shadow-lg" alt="Team Member">
                <h5 class="text-xl font-semibold text-slate-800 mb-2">คุณสมหญิง รักดี</h5>
                <p class="text-slate-600 mb-4">Lead Developer</p>
                <p class="text-slate-600 leading-relaxed mb-6">
                    นักพัฒนาหลักที่เชี่ยวชาญใน Laravel และ PHP
                    มีประสบการณ์ในการสร้างระบบขนาดใหญ่
                </p>
                <div class="flex justify-center space-x-4">
                    <a href="#" class="w-10 h-10 bg-blue-600 text-white rounded-full flex items-center justify-center hover:bg-blue-700 transition-colors">
                        <i class="fab fa-linkedin-in"></i>
                    </a>
                    <a href="#" class="w-10 h-10 bg-sky-500 text-white rounded-full flex items-center justify-center hover:bg-sky-600 transition-colors">
                        <i class="fab fa-twitter"></i>
                    </a>
                    <a href="#" class="w-10 h-10 bg-slate-800 text-white rounded-full flex items-center justify-center hover:bg-slate-900 transition-colors">
                        <i class="fab fa-github"></i>
                    </a>
                </div>
            </div>
            
            <!-- Team Member 3 -->
            <div class="bg-white rounded-2xl p-8 shadow-lg hover:shadow-xl transition-all duration-300 border border-slate-200 hover:-translate-y-2 text-center">
                <img src="https://placehold.co/150" class="w-24 h-24 rounded-full mx-auto mb-6 shadow-lg" alt="Team Member">
                <h5 class="text-xl font-semibold text-slate-800 mb-2">คุณสมศักดิ์ เก่งดี</h5>
                <p class="text-slate-600 mb-4">UI/UX Designer</p>
                <p class="text-slate-600 leading-relaxed mb-6">
                    นักออกแบบที่สร้างประสบการณ์ผู้ใช้ที่ดี
                    และอินเทอร์เฟซที่สวยงามใช้งานง่าย
                </p>
                <div class="flex justify-center space-x-4">
                    <a href="#" class="w-10 h-10 bg-blue-600 text-white rounded-full flex items-center justify-center hover:bg-blue-700 transition-colors">
                        <i class="fab fa-linkedin-in"></i>
                    </a>
                    <a href="#" class="w-10 h-10 bg-sky-500 text-white rounded-full flex items-center justify-center hover:bg-sky-600 transition-colors">
                        <i class="fab fa-twitter"></i>
                    </a>
                    <a href="#" class="w-10 h-10 bg-pink-500 text-white rounded-full flex items-center justify-center hover:bg-pink-600 transition-colors">
                        <i class="fab fa-dribbble"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Values Section -->
<section class="py-20 bg-slate-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-4xl lg:text-5xl font-bold text-slate-800 mb-6">ค่านิยมของเรา</h2>
            <p class="text-xl text-slate-600 max-w-3xl mx-auto">
                หลักการที่เรายึดถือในการทำงาน
            </p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
            <!-- Value 1 -->
            <div class="text-center">
                <div class="w-20 h-20 bg-gradient-success rounded-2xl flex items-center justify-center text-white text-3xl mb-6 mx-auto">
                    <i class="fas fa-handshake"></i>
                </div>
                <h5 class="text-xl font-semibold text-slate-800 mb-4">ความไว้วางใจ</h5>
                <p class="text-slate-600 leading-relaxed">สร้างความไว้วางใจกับลูกค้าด้วยการให้บริการที่ดี</p>
            </div>
            
            <!-- Value 2 -->
            <div class="text-center">
                <div class="w-20 h-20 bg-gradient-warning rounded-2xl flex items-center justify-center text-white text-3xl mb-6 mx-auto">
                    <i class="fas fa-lightbulb"></i>
                </div>
                <h5 class="text-xl font-semibold text-slate-800 mb-4">นวัตกรรม</h5>
                <p class="text-slate-600 leading-relaxed">พัฒนานวัตกรรมใหม่ๆ เพื่อตอบสนองความต้องการ</p>
            </div>
            
            <!-- Value 3 -->
            <div class="text-center">
                <div class="w-20 h-20 bg-gradient-info rounded-2xl flex items-center justify-center text-white text-3xl mb-6 mx-auto">
                    <i class="fas fa-star"></i>
                </div>
                <h5 class="text-xl font-semibold text-slate-800 mb-4">คุณภาพ</h5>
                <p class="text-slate-600 leading-relaxed">มุ่งมั่นให้บริการที่มีคุณภาพสูงสุด</p>
            </div>
            
            <!-- Value 4 -->
            <div class="text-center">
                <div class="w-20 h-20 bg-gradient-primary rounded-2xl flex items-center justify-center text-white text-3xl mb-6 mx-auto">
                    <i class="fas fa-users"></i>
                </div>
                <h5 class="text-xl font-semibold text-slate-800 mb-4">ทีมเวิร์ค</h5>
                <p class="text-slate-600 leading-relaxed">ทำงานร่วมกันเป็นทีมเพื่อเป้าหมายเดียวกัน</p>
            </div>
        </div>
    </div>
</section>
@endsection
