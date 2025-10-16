<!-- System Info Tab -->
<div class="tab-pane fade" id="system-info" role="tabpanel">
    <div class="settings-card">
        <div class="card-header">
            <h5>ข้อมูลระบบ</h5>
        </div>
        <div class="card-body">
            <!-- Pass PHP data to JavaScript -->
            <script>
                window.phpVersion = '{{ PHP_VERSION }}';
                window.laravelVersion = '{{ app()->version() }}';
                window.dbType = '{{ config("database.default") }}';
                window.appEnv = '{{ config("app.env") }}';
                window.debugMode = {{ config("app.debug") ? 'true' : 'false' }};
                window.appKey = '{{ substr(config("app.key"), 0, 20) }}...';
                
                // Real system data
                window.memoryLimit = '{{ ini_get("memory_limit") }}';
                window.memoryUsage = '{{ memory_get_usage(true) }}';
                window.memoryPeak = '{{ memory_get_peak_usage(true) }}';
                window.maxExecutionTime = '{{ ini_get("max_execution_time") }}';
                window.uploadMaxFilesize = '{{ ini_get("upload_max_filesize") }}';
                window.postMaxSize = '{{ ini_get("post_max_size") }}';
                
                // Database info
                window.dbHost = '{{ config("database.connections.mysql.host") }}';
                window.dbPort = '{{ config("database.connections.mysql.port") }}';
                window.dbDatabase = '{{ config("database.connections.mysql.database") }}';
                
                // App info
                window.appName = '{{ config("app.name") }}';
                window.appUrl = '{{ config("app.url") }}';
                window.appTimezone = '{{ config("app.timezone") }}';
                window.appLocale = '{{ config("app.locale") }}';
                
                // Server info
                window.serverName = '{{ $_SERVER["SERVER_NAME"] ?? "Unknown" }}';
                window.serverTime = '{{ now()->format("Y-m-d H:i:s") }}';
            </script>
            <!-- Server Information -->
            <div class="row g-3 mb-4">
                <div class="col-12">
                    <h6 class="text-primary mb-3">
                        <i class="fas fa-server me-2"></i>ข้อมูลเซิร์ฟเวอร์
                    </h6>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Server Name</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="fas fa-globe"></i>
                        </span>
                        <input type="text" class="form-control" id="server-name" value="-" readonly>
                    </div>
                </div>
                <div class="col-md-6">
                    <label class="form-label">PHP Version</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="fas fa-code"></i>
                        </span>
                        <input type="text" class="form-control" id="php-version" value="-" readonly>
                    </div>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Laravel Version</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="fas fa-cogs"></i>
                        </span>
                        <input type="text" class="form-control" id="laravel-version" value="-" readonly>
                    </div>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Server Time</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="fas fa-clock"></i>
                        </span>
                        <input type="text" class="form-control" id="server-time" value="-" readonly>
                    </div>
                </div>
            </div>

            <!-- System Resources -->
            <div class="row g-3 mb-4">
                <div class="col-12">
                    <h6 class="text-primary mb-3">
                        <i class="fas fa-memory me-2"></i>ทรัพยากรระบบ
                    </h6>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Memory Usage</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="fas fa-memory"></i>
                        </span>
                        <input type="text" class="form-control" id="memory-usage" value="-" readonly>
                    </div>
                    <div class="progress mt-2">
                        <div class="progress-bar" id="memory-progress" role="progressbar" style="width: 0%"></div>
                    </div>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Disk Usage</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="fas fa-hdd"></i>
                        </span>
                        <input type="text" class="form-control" id="disk-usage" value="-" readonly>
                    </div>
                    <div class="progress mt-2">
                        <div class="progress-bar" id="disk-progress" role="progressbar" style="width: 0%"></div>
                    </div>
                </div>
                <div class="col-md-6">
                    <label class="form-label">CPU Load</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="fas fa-microchip"></i>
                        </span>
                        <input type="text" class="form-control" id="cpu-load" value="-" readonly>
                    </div>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Active Users</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="fas fa-users"></i>
                        </span>
                        <input type="text" class="form-control" id="active-users" value="-" readonly>
                    </div>
                </div>
            </div>

            <!-- Database Information -->
            <div class="row g-3 mb-4">
                <div class="col-12">
                    <h6 class="text-primary mb-3">
                        <i class="fas fa-database me-2"></i>ข้อมูลฐานข้อมูล
                    </h6>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Database Type</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="fas fa-database"></i>
                        </span>
                        <input type="text" class="form-control" id="db-type" value="-" readonly>
                    </div>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Database Size</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="fas fa-table"></i>
                        </span>
                        <input type="text" class="form-control" id="db-size" value="-" readonly>
                    </div>
                </div>
                <div class="col-md-6">
                    <label class="form-label">DB Connections</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="fas fa-link"></i>
                        </span>
                        <input type="text" class="form-control" id="db-connections" value="-" readonly>
                    </div>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Last Backup</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="fas fa-clock"></i>
                        </span>
                        <input type="text" class="form-control" id="last-backup" value="-" readonly>
                    </div>
                </div>
            </div>

            <!-- Application Information -->
            <div class="row g-3 mb-4">
                <div class="col-12">
                    <h6 class="text-primary mb-3">
                        <i class="fas fa-info-circle me-2"></i>ข้อมูลแอปพลิเคชัน
                    </h6>
                </div>
                <div class="col-md-6">
                    <label class="form-label">App Environment</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="fas fa-folder"></i>
                        </span>
                        <input type="text" class="form-control" id="app-env" value="-" readonly>
                    </div>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Debug Mode</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="fas fa-bug"></i>
                        </span>
                        <input type="text" class="form-control" id="debug-mode" value="-" readonly>
                    </div>
                </div>
                <div class="col-md-6">
                    <label class="form-label">App Key</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="fas fa-key"></i>
                        </span>
                        <input type="text" class="form-control" id="app-key" value="-" readonly>
                    </div>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Uptime</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="fas fa-calendar"></i>
                        </span>
                        <input type="text" class="form-control" id="uptime" value="-" readonly>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="row g-3">
                <div class="col-12">
                    <div class="d-flex gap-2 flex-wrap">
                        <button type="button" class="btn btn-primary" onclick="refreshSystemInfo()">
                            <i class="fas fa-sync-alt me-2"></i>รีเฟรชข้อมูล
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
