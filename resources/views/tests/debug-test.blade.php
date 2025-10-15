<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ทดสอบระดับ Debug - {{ config('app.name') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Prompt', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }
        .debug-container {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 15px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            margin: 20px;
            padding: 30px;
        }
        .debug-level {
            padding: 15px;
            margin: 10px 0;
            border-radius: 10px;
            border-left: 5px solid;
        }
        .debug-off { background: #f8f9fa; border-left-color: #6c757d; }
        .debug-minimal { background: #fff3cd; border-left-color: #ffc107; }
        .debug-standard { background: #d1ecf1; border-left-color: #17a2b8; }
        .debug-verbose { background: #d4edda; border-left-color: #28a745; }
        .debug-development { background: #f8d7da; border-left-color: #dc3545; }
        .test-section {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 10px;
            margin: 20px 0;
        }
        .error-demo {
            background: #fff;
            border: 1px solid #dee2e6;
            border-radius: 5px;
            padding: 15px;
            margin: 10px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="debug-container">
            <div class="text-center mb-4">
                <h1><i class="fas fa-bug me-2"></i>ทดสอบระดับ Debug</h1>
                <p class="text-muted">หน้าทดสอบการทำงานของระดับ Debug ที่เลือก</p>
            </div>

            <!-- Current Debug Status -->
            <div class="test-section">
                <h3><i class="fas fa-info-circle me-2"></i>สถานะ Debug ปัจจุบัน</h3>
                <div class="row">
                    <div class="col-md-6">
                        <strong>Debug Mode:</strong> 
                        <span class="badge {{ config('app.debug') ? 'bg-success' : 'bg-danger' }}">
                            {{ config('app.debug') ? 'เปิดใช้งาน' : 'ปิดใช้งาน' }}
                        </span>
                    </div>
                    <div class="col-md-6">
                        <strong>Debug Level:</strong> 
                        <span class="badge bg-primary">{{ config('app.debug_level', 'standard') }}</span>
                    </div>
                </div>
            </div>

            <!-- Debug Level Information -->
            <div class="test-section">
                <h3><i class="fas fa-list me-2"></i>ข้อมูลระดับ Debug</h3>
                <div class="debug-level debug-{{ config('app.debug_level', 'standard') }}">
                    <h5>
                        @switch(config('app.debug_level', 'standard'))
                            @case('off')
                                <i class="fas fa-times-circle text-muted"></i> ปิดใช้งาน
                                @break
                            @case('minimal')
                                <i class="fas fa-exclamation-triangle text-warning"></i> ขั้นต่ำ
                                @break
                            @case('standard')
                                <i class="fas fa-info-circle text-info"></i> มาตรฐาน
                                @break
                            @case('verbose')
                                <i class="fas fa-search text-success"></i> ละเอียด
                                @break
                            @case('development')
                                <i class="fas fa-code text-danger"></i> พัฒนา
                                @break
                        @endswitch
                    </h5>
                    <p class="mb-0">
                        @switch(config('app.debug_level', 'standard'))
                            @case('off')
                                ไม่แสดงข้อมูล debug ใดๆ เหมาะสำหรับ production
                                @break
                            @case('minimal')
                                แสดงข้อผิดพลาดพื้นฐานเท่านั้น เหมาะสำหรับ production ที่ต้องการ error reporting
                                @break
                            @case('standard')
                                แสดงข้อผิดพลาดและข้อมูล debug ปกติ เหมาะสำหรับ staging/testing
                                @break
                            @case('verbose')
                                แสดงข้อมูล debug ครบถ้วน เหมาะสำหรับ debugging
                                @break
                            @case('development')
                                แสดงข้อมูลทั้งหมด เหมาะสำหรับการพัฒนา
                                @break
                        @endswitch
                    </p>
                </div>
            </div>

            <!-- Test Buttons -->
            <div class="test-section">
                <h3><i class="fas fa-play me-2"></i>ทดสอบการทำงาน</h3>
                <div class="row">
                    <div class="col-md-3">
                        <button class="btn btn-warning w-100" onclick="testError()">
                            <i class="fas fa-exclamation-triangle me-2"></i>ทดสอบ Error
                        </button>
                    </div>
                    <div class="col-md-3">
                        <button class="btn btn-info w-100" onclick="testLogging()">
                            <i class="fas fa-file-alt me-2"></i>ทดสอบ Logging
                        </button>
                    </div>
                    <div class="col-md-3">
                        <button class="btn btn-success w-100" onclick="testDatabase()">
                            <i class="fas fa-database me-2"></i>ทดสอบ Database
                        </button>
                    </div>
                    <div class="col-md-3">
                        <button class="btn btn-primary w-100" onclick="testPerformance()">
                            <i class="fas fa-tachometer-alt me-2"></i>ทดสอบ Performance
                        </button>
                    </div>
                </div>
            </div>

            <!-- Test Results -->
            <div class="test-section">
                <h3><i class="fas fa-chart-line me-2"></i>ผลการทดสอบ</h3>
                <div id="testResults">
                    <p class="text-muted">กดปุ่มทดสอบเพื่อดูผลลัพธ์</p>
                </div>
            </div>

            <!-- Debug Information Display -->
            @if(config('app.debug'))
                <div class="test-section">
                    <h3><i class="fas fa-cogs me-2"></i>ข้อมูล Debug</h3>
                    <div class="row">
                        <div class="col-md-6">
                            <h5>ข้อมูลระบบ</h5>
                            <ul class="list-unstyled">
                                <li><strong>PHP Version:</strong> {{ PHP_VERSION }}</li>
                                <li><strong>Laravel Version:</strong> {{ app()->version() }}</li>
                                <li><strong>Environment:</strong> {{ app()->environment() }}</li>
                                <li><strong>Memory Usage:</strong> {{ number_format(memory_get_usage(true) / 1024 / 1024, 2) }} MB</li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <h5>ข้อมูลการตั้งค่า</h5>
                            <ul class="list-unstyled">
                                <li><strong>App Name:</strong> {{ config('app.name') }}</li>
                                <li><strong>App URL:</strong> {{ config('app.url') }}</li>
                                <li><strong>Timezone:</strong> {{ config('app.timezone') }}</li>
                                <li><strong>Locale:</strong> {{ config('app.locale') }}</li>
                            </ul>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Navigation -->
            <div class="text-center mt-4">
                <a href="/admin/settings" class="btn btn-primary me-2">
                    <i class="fas fa-cog me-2"></i>กลับไปตั้งค่า
                </a>
                <a href="/admin" class="btn btn-secondary">
                    <i class="fas fa-home me-2"></i>กลับหน้าหลัก
                </a>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        async function testError() {
            const results = document.getElementById('testResults');
            results.innerHTML = '<div class="alert alert-warning"><i class="fas fa-exclamation-triangle me-2"></i>กำลังทดสอบ Error...</div>';
            
            try {
                const response = await fetch('/api/debug-test/error');
                const data = await response.json();
                results.innerHTML = `
                    <div class="error-demo">
                        <h5><i class="fas fa-bug me-2"></i>ผลการทดสอบ Error</h5>
                        <p><strong>Response:</strong> ${data.message}</p>
                        <p><strong>Debug Level:</strong> ${getDebugLevel()}</p>
                        <p><strong>แสดง Stack Trace:</strong> ${shouldShowStackTrace() ? 'ใช่' : 'ไม่'}</p>
                    </div>
                `;
            } catch (error) {
                results.innerHTML = `
                    <div class="error-demo">
                        <h5><i class="fas fa-bug me-2"></i>ผลการทดสอบ Error</h5>
                        <p><strong>Error Message:</strong> ${error.message}</p>
                        <p><strong>Debug Level:</strong> ${getDebugLevel()}</p>
                        <p><strong>แสดง Stack Trace:</strong> ${shouldShowStackTrace() ? 'ใช่' : 'ไม่'}</p>
                        <p><strong>Status:</strong> Error ถูกจับได้ตามที่คาดหวัง</p>
                    </div>
                `;
            }
        }

        async function testLogging() {
            const results = document.getElementById('testResults');
            results.innerHTML = '<div class="alert alert-info"><i class="fas fa-file-alt me-2"></i>กำลังทดสอบ Logging...</div>';
            
            try {
                const response = await fetch('/api/debug-test/logging');
                const data = await response.json();
                results.innerHTML = `
                    <div class="error-demo">
                        <h5><i class="fas fa-file-alt me-2"></i>ผลการทดสอบ Logging</h5>
                        <p><strong>Message:</strong> ${data.message}</p>
                        <p><strong>Debug Level:</strong> ${data.debug_level}</p>
                        <p><strong>บันทึก Log:</strong> ${data.logs_written ? 'ใช่' : 'ไม่'}</p>
                        <p><strong>ระดับ Log:</strong> ${getLogLevel()}</p>
                        <p><strong>ไฟล์ Log:</strong> storage/logs/laravel.log</p>
                    </div>
                `;
            } catch (error) {
                results.innerHTML = `
                    <div class="error-demo">
                        <h5><i class="fas fa-file-alt me-2"></i>ผลการทดสอบ Logging</h5>
                        <p><strong>Error:</strong> ${error.message}</p>
                        <p><strong>Debug Level:</strong> ${getDebugLevel()}</p>
                    </div>
                `;
            }
        }

        async function testDatabase() {
            const results = document.getElementById('testResults');
            results.innerHTML = '<div class="alert alert-success"><i class="fas fa-database me-2"></i>กำลังทดสอบ Database...</div>';
            
            try {
                const response = await fetch('/api/debug-test/database');
                const data = await response.json();
                results.innerHTML = `
                    <div class="error-demo">
                        <h5><i class="fas fa-database me-2"></i>ผลการทดสอบ Database</h5>
                        <p><strong>Message:</strong> ${data.message}</p>
                        <p><strong>Debug Level:</strong> ${data.debug_level}</p>
                        <p><strong>Total Users:</strong> ${data.total_users}</p>
                        <p><strong>แสดง SQL Queries:</strong> ${shouldShowQueries() ? 'ใช่' : 'ไม่'}</p>
                        <p><strong>แสดง Query Time:</strong> ${shouldShowQueryTime() ? 'ใช่' : 'ไม่'}</p>
                        <p><strong>แสดง Query Parameters:</strong> ${shouldShowQueryParams() ? 'ใช่' : 'ไม่'}</p>
                    </div>
                `;
            } catch (error) {
                results.innerHTML = `
                    <div class="error-demo">
                        <h5><i class="fas fa-database me-2"></i>ผลการทดสอบ Database</h5>
                        <p><strong>Error:</strong> ${error.message}</p>
                        <p><strong>Debug Level:</strong> ${getDebugLevel()}</p>
                    </div>
                `;
            }
        }

        async function testPerformance() {
            const results = document.getElementById('testResults');
            results.innerHTML = '<div class="alert alert-primary"><i class="fas fa-tachometer-alt me-2"></i>กำลังทดสอบ Performance...</div>';
            
            try {
                const response = await fetch('/api/debug-test/performance');
                const data = await response.json();
                results.innerHTML = `
                    <div class="error-demo">
                        <h5><i class="fas fa-tachometer-alt me-2"></i>ผลการทดสอบ Performance</h5>
                        <p><strong>Message:</strong> ${data.message}</p>
                        <p><strong>Debug Level:</strong> ${data.debug_level}</p>
                        <p><strong>Execution Time:</strong> ${data.execution_time_ms} ms</p>
                        <p><strong>Memory Usage:</strong> ${data.memory_usage_mb} MB</p>
                        <p><strong>Peak Memory:</strong> ${data.peak_memory_mb} MB</p>
                        <p><strong>แสดง Performance Metrics:</strong> ${shouldShowPerformance() ? 'ใช่' : 'ไม่'}</p>
                    </div>
                `;
            } catch (error) {
                results.innerHTML = `
                    <div class="error-demo">
                        <h5><i class="fas fa-tachometer-alt me-2"></i>ผลการทดสอบ Performance</h5>
                        <p><strong>Error:</strong> ${error.message}</p>
                        <p><strong>Debug Level:</strong> ${getDebugLevel()}</p>
                    </div>
                `;
            }
        }

        function getDebugLevel() {
            return '{{ config("app.debug_level", "standard") }}';
        }

        function shouldShowStackTrace() {
            const level = getDebugLevel();
            return ['standard', 'verbose', 'development'].includes(level);
        }

        function shouldLog() {
            const level = getDebugLevel();
            return level !== 'off';
        }

        function getLogLevel() {
            const level = getDebugLevel();
            switch(level) {
                case 'off': return 'ไม่บันทึก';
                case 'minimal': return 'ERROR, CRITICAL';
                case 'standard': return 'ERROR, WARNING, INFO';
                case 'verbose': return 'ERROR, WARNING, INFO, DEBUG';
                case 'development': return 'ทั้งหมด';
                default: return 'INFO';
            }
        }

        function shouldShowQueries() {
            const level = getDebugLevel();
            return ['verbose', 'development'].includes(level);
        }

        function shouldShowQueryTime() {
            const level = getDebugLevel();
            return ['verbose', 'development'].includes(level);
        }

        function shouldShowQueryParams() {
            const level = getDebugLevel();
            return level === 'development';
        }

        function shouldShowPerformance() {
            const level = getDebugLevel();
            return ['verbose', 'development'].includes(level);
        }
    </script>
</body>
</html>
