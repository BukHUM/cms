<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@setting('site_name') - @setting('site_description')</title>
    <meta name="description" content="@setting('site_description')">
    <meta name="keywords" content="@setting('site_keywords')">
    <meta name="author" content="@setting('site_author')">
</head>
<body>
    <header>
        <h1>@setting('site_name')</h1>
        <p>@setting('site_description')</p>
        
        @ifsetting('maintenance_mode')
            <div class="alert alert-warning">
                @setting('maintenance_message')
            </div>
        @endsetting
        
        @ifnotsetting('enable_registration')
            <div class="alert alert-info">
                การลงทะเบียนถูกปิดใช้งานชั่วคราว
            </div>
        @endsetting
    </header>
    
    <main>
        <section>
            <h2>ข้อมูลเว็บไซต์</h2>
            <ul>
                <li>ชื่อเว็บไซต์: @setting('site_name')</li>
                <li>เวอร์ชัน: @setting('site_version')</li>
                <li>ภาษา: @setting('site_language')</li>
                <li>เขตเวลา: @setting('site_timezone')</li>
                <li>สกุลเงิน: @setting('site_currency')</li>
            </ul>
        </section>
        
        <section>
            <h2>การตั้งค่าระบบ</h2>
            <ul>
                <li>ขนาดไฟล์สูงสุด: @setting('max_upload_size') MB</li>
                <li>จำนวนรายการต่อหน้า: @setting('default_pagination')</li>
                <li>ประเภทไฟล์ที่อนุญาต: @setting('allowed_file_types')</li>
            </ul>
        </section>
        
        <section>
            <h2>สถานะระบบ</h2>
            <ul>
                <li>โหมดบำรุงรักษา: 
                    @ifsetting('maintenance_mode')
                        <span class="text-danger">เปิดใช้งาน</span>
                    @else
                        <span class="text-success">ปิดใช้งาน</span>
                    @endsetting
                </li>
                <li>การลงทะเบียน: 
                    @ifsetting('enable_registration')
                        <span class="text-success">เปิดใช้งาน</span>
                    @else
                        <span class="text-danger">ปิดใช้งาน</span>
                    @endsetting
                </li>
                <li>ความคิดเห็น: 
                    @ifsetting('enable_comments')
                        <span class="text-success">เปิดใช้งาน</span>
                    @else
                        <span class="text-danger">ปิดใช้งาน</span>
                    @endsetting
                </li>
            </ul>
        </section>
    </main>
    
    <footer>
        <p>&copy; {{ date('Y') }} @setting('site_name'). All rights reserved.</p>
        <p>Version @setting('site_version')</p>
    </footer>
</body>
</html>
