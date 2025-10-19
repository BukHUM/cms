<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', setting('site_name', 'CMS Frontend'))</title>
    
    @if(setting('site_favicon'))
    <link rel="icon" type="image/png" href="{{ asset('storage/' . setting('site_favicon')) }}">
    @else
    <link rel="icon" type="image/png" href="{{ asset('favicon.ico') }}">
    @endif
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="bg-gray-50 font-prompt">
    <!-- Navigation -->
    <nav class="bg-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-4">
                <div class="flex items-center">
                    <a href="{{ route('frontend.home') }}" class="text-2xl font-bold text-gray-900">
                        <i class="fas fa-home mr-2"></i>
                        {{ setting('site_name', 'CMS Frontend') }}
                    </a>
                </div>
                
                <div class="hidden md:flex items-center space-x-8">
                    <a href="{{ route('frontend.home') }}" class="text-gray-700 hover:text-blue-600 {{ request()->routeIs('frontend.home') ? 'text-blue-600' : '' }}">
                        <i class="fas fa-home mr-1"></i>
                        หน้าแรก
                    </a>
                    <a href="#" class="text-gray-700 hover:text-blue-600">
                        <i class="fas fa-newspaper mr-1"></i>
                        บทความ
                    </a>
                    <a href="#" class="text-gray-700 hover:text-blue-600">
                        <i class="fas fa-images mr-1"></i>
                        แกลเลอรี่
                    </a>
                    <a href="#" class="text-gray-700 hover:text-blue-600">
                        <i class="fas fa-envelope mr-1"></i>
                        ติดต่อ
                    </a>
                </div>
                
                <div class="flex items-center space-x-4">
                    @auth
                        <!-- ถ้า login แล้ว แสดงข้อมูลผู้ใช้และปุ่มต่างๆ -->
                        <div class="flex items-center space-x-2 sm:space-x-3">
                            <div class="text-right hidden lg:block">
                                <p class="text-sm font-medium text-gray-900">{{ Auth::user()->name }}</p>
                                <p class="text-xs text-gray-500">{{ Auth::user()->roles->first()->name ?? 'User' }}</p>
                            </div>
                            <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center">
                                <i class="fas fa-user text-white text-sm"></i>
                            </div>
                            <a href="{{ route('backend.dashboard') }}" class="bg-green-600 text-white px-3 py-2 rounded-lg hover:bg-green-700 transition-colors text-sm">
                                <i class="fas fa-cogs mr-1"></i>
                                <span class="hidden sm:inline">Backend</span>
                            </a>
                            <a href="{{ route('logout') }}" class="bg-red-600 text-white px-3 py-2 rounded-lg hover:bg-red-700 transition-colors text-sm" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <i class="fas fa-sign-out-alt mr-1"></i>
                                <span class="hidden sm:inline">ออกจากระบบ</span>
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                                @csrf
                            </form>
                        </div>
                    @else
                        <!-- ถ้ายังไม่ login แสดงปุ่มเข้าสู่ระบบ -->
                        <a href="{{ route('login') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                            <i class="fas fa-sign-in-alt mr-1"></i>
                            เข้าสู่ระบบ
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div>
                    <h3 class="text-lg font-semibold mb-4">
                        <i class="fas fa-home mr-2"></i>
                        {{ setting('site_name', 'CMS Frontend') }}
                    </h3>
                    <p class="text-gray-400 text-sm">
                        ระบบจัดการเนื้อหาแบบครบวงจร พร้อมใช้งานง่าย
                    </p>
                </div>
                
                <div>
                    <h4 class="font-semibold mb-4">เมนูหลัก</h4>
                    <ul class="space-y-2 text-sm text-gray-400">
                        <li><a href="#" class="hover:text-white">หน้าแรก</a></li>
                        <li><a href="#" class="hover:text-white">บทความ</a></li>
                        <li><a href="#" class="hover:text-white">แกลเลอรี่</a></li>
                        <li><a href="#" class="hover:text-white">ติดต่อ</a></li>
                    </ul>
                </div>
                
                <div>
                    <h4 class="font-semibold mb-4">ข้อมูลเพิ่มเติม</h4>
                    <ul class="space-y-2 text-sm text-gray-400">
                        <li><a href="#" class="hover:text-white">เกี่ยวกับเรา</a></li>
                        <li><a href="#" class="hover:text-white">นโยบายความเป็นส่วนตัว</a></li>
                        <li><a href="#" class="hover:text-white">เงื่อนไขการใช้งาน</a></li>
                    </ul>
                </div>
                
                <div>
                    <h4 class="font-semibold mb-4">ติดต่อเรา</h4>
                    <div class="space-y-2 text-sm text-gray-400">
                        <p><i class="fas fa-envelope mr-2"></i>contact@example.com</p>
                        <p><i class="fas fa-phone mr-2"></i>02-123-4567</p>
                        <p><i class="fas fa-map-marker-alt mr-2"></i>กรุงเทพมหานคร</p>
                    </div>
                </div>
            </div>
            
            <div class="border-t border-gray-700 mt-8 pt-8 text-center text-sm text-gray-400">
                <p>&copy; {{ date('Y') }} {{ setting('site_name', 'CMS Frontend') }}. All rights reserved.</p>
            </div>
        </div>
    </footer>

    @stack('scripts')
</body>
</html>
