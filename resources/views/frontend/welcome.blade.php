@extends('frontend.layouts.app')

@section('title', 'หน้าแรก - CMS Frontend')

@section('content')
<!-- Hero Section -->
<section class="bg-gradient-to-r from-blue-600 to-purple-600 text-white py-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h1 class="text-4xl md:text-6xl font-bold mb-6">
            ยินดีต้อนรับสู่ CMS Frontend
        </h1>
        <p class="text-xl md:text-2xl mb-8 text-blue-100">
            ระบบจัดการเนื้อหาแบบครบวงจร พร้อมใช้งานง่าย
        </p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="#" class="bg-white text-blue-600 px-8 py-3 rounded-lg font-semibold hover:bg-gray-100 transition-colors">
                <i class="fas fa-play mr-2"></i>
                เริ่มต้นใช้งาน
            </a>
            <a href="#" class="border-2 border-white text-white px-8 py-3 rounded-lg font-semibold hover:bg-white hover:text-blue-600 transition-colors">
                <i class="fas fa-info-circle mr-2"></i>
                เรียนรู้เพิ่มเติม
            </a>
        </div>
    </div>
</section>

<!-- Features Section -->
<section class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                ฟีเจอร์หลักของระบบ
            </h2>
            <p class="text-xl text-gray-600">
                ระบบจัดการเนื้อหาที่ครบครันและใช้งานง่าย
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="text-center p-6">
                <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-6">
                    <i class="fas fa-edit text-blue-600 text-2xl"></i>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-4">จัดการเนื้อหา</h3>
                <p class="text-gray-600">
                    สร้าง แก้ไข และจัดการเนื้อหาอย่างง่ายดาย ด้วยระบบ editor ที่ทันสมัย
                </p>
            </div>

            <div class="text-center p-6">
                <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-6">
                    <i class="fas fa-users text-green-600 text-2xl"></i>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-4">จัดการผู้ใช้</h3>
                <p class="text-gray-600">
                    ระบบจัดการผู้ใช้และสิทธิ์การเข้าถึงที่ยืดหยุ่นและปลอดภัย
                </p>
            </div>

            <div class="text-center p-6">
                <div class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-6">
                    <i class="fas fa-chart-line text-purple-600 text-2xl"></i>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-4">รายงานและสถิติ</h3>
                <p class="text-gray-600">
                    ติดตามและวิเคราะห์ข้อมูลการใช้งานด้วยรายงานที่ครบถ้วน
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Latest Articles Section -->
<section class="py-20 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                บทความล่าสุด
            </h2>
            <p class="text-xl text-gray-600">
                อัปเดตเนื้อหาใหม่ๆ ที่น่าสนใจ
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="h-48 bg-gradient-to-r from-blue-400 to-blue-600"></div>
                <div class="p-6">
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">
                        วิธีการใช้งาน CMS
                    </h3>
                    <p class="text-gray-600 mb-4">
                        เรียนรู้วิธีการใช้งานระบบจัดการเนื้อหาเบื้องต้น
                    </p>
                    <a href="#" class="text-blue-600 hover:text-blue-800 font-semibold">
                        อ่านต่อ <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="h-48 bg-gradient-to-r from-green-400 to-green-600"></div>
                <div class="p-6">
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">
                        เทคนิคการเขียนเนื้อหา
                    </h3>
                    <p class="text-gray-600 mb-4">
                        เคล็ดลับการเขียนเนื้อหาที่น่าสนใจและมีคุณภาพ
                    </p>
                    <a href="#" class="text-blue-600 hover:text-blue-800 font-semibold">
                        อ่านต่อ <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="h-48 bg-gradient-to-r from-purple-400 to-purple-600"></div>
                <div class="p-6">
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">
                        การจัดการ SEO
                    </h3>
                    <p class="text-gray-600 mb-4">
                        วิธีการปรับแต่งเนื้อหาให้เหมาะสมกับ SEO
                    </p>
                    <a href="#" class="text-blue-600 hover:text-blue-800 font-semibold">
                        อ่านต่อ <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-20 bg-blue-600">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-3xl md:text-4xl font-bold text-white mb-6">
            พร้อมเริ่มต้นใช้งานแล้วหรือยัง?
        </h2>
        <p class="text-xl text-blue-100 mb-8">
            เข้าสู่ระบบเพื่อเริ่มจัดการเนื้อหาของคุณ
        </p>
        <a href="{{ route('backend.dashboard') }}" class="bg-white text-blue-600 px-8 py-3 rounded-lg font-semibold hover:bg-gray-100 transition-colors inline-block">
            <i class="fas fa-sign-in-alt mr-2"></i>
            เข้าสู่ระบบ
        </a>
    </div>
</section>
@endsection