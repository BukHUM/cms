<!-- Performance Settings -->
<div class="tab-pane fade" id="performance" role="tabpanel">
    <div class="settings-card">
        <div class="card-header">
            <h5>การตั้งค่า Performance</h5>
        </div>
        <div class="card-body">
            <form id="performanceSettingsForm">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="cacheEnabled" class="form-label">เปิดใช้งาน Cache</label>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="cacheEnabled" checked>
                            <label class="form-check-label" for="cacheEnabled" id="cacheEnabledLabel">
                                เปิดใช้งาน
                            </label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label for="cacheDriver" class="form-label">Cache Driver</label>
                        <select class="form-select" id="cacheDriver">
                            <option value="file" selected>File</option>
                            <option value="redis">Redis</option>
                            <option value="memcached">Memcached</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="cacheTTL" class="form-label">Cache TTL (นาที)</label>
                        <input type="number" class="form-control" id="cacheTTL" value="60" min="1" max="1440">
                    </div>
                    <div class="col-md-6">
                        <label for="queryLogging" class="form-label">บันทึก Query Log</label>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="queryLogging">
                            <label class="form-check-label" for="queryLogging" id="queryLoggingLabel">
                                เปิดใช้งาน
                            </label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label for="slowQueryThreshold" class="form-label">Slow Query Threshold (ms)</label>
                        <input type="number" class="form-control" id="slowQueryThreshold" value="1000" min="100" max="10000">
                    </div>
                    <div class="col-md-6">
                        <label for="memoryLimit" class="form-label">Memory Limit (MB)</label>
                        <input type="number" class="form-control" id="memoryLimit" value="256" min="128" max="2048">
                    </div>
                    <div class="col-md-6">
                        <label for="maxExecutionTime" class="form-label">Max Execution Time (วินาที)</label>
                        <input type="number" class="form-control" id="maxExecutionTime" value="30" min="10" max="300">
                    </div>
                    <div class="col-md-6">
                        <label for="compressionEnabled" class="form-label">เปิดใช้งาน Compression</label>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="compressionEnabled" checked>
                            <label class="form-check-label" for="compressionEnabled" id="compressionEnabledLabel">
                                เปิดใช้งาน
                            </label>
                        </div>
                    </div>
                </div>
                <!-- Desktop Actions -->
                <div class="mt-4 d-none d-md-block">
                    <button type="button" class="btn btn-info me-2" onclick="clearCache()">
                        <i class="fas fa-broom me-2"></i>
                        ล้าง Cache
                    </button>
                    <button type="button" class="btn btn-warning me-2" onclick="runPerformanceTest()">
                        <i class="fas fa-play me-2"></i>
                        ทดสอบประสิทธิภาพ
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>
                        บันทึกการตั้งค่า
                    </button>
                </div>
                
                <!-- Mobile Actions -->
                <div class="mt-4 d-md-none">
                    <div class="mobile-actions">
                        <div class="mobile-primary-action">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fas fa-save me-2"></i>
                                บันทึกการตั้งค่า
                            </button>
                        </div>
                        <div class="mobile-secondary-actions">
                            <div class="row g-2">
                                <div class="col-6">
                                    <button type="button" class="btn btn-info w-100" onclick="clearCache()">
                                        <i class="fas fa-broom me-1"></i>
                                        <span class="d-none d-sm-inline">ล้าง Cache</span>
                                        <span class="d-sm-none">ล้าง</span>
                                    </button>
                                </div>
                                <div class="col-6">
                                    <button type="button" class="btn btn-warning w-100" onclick="runPerformanceTest()">
                                        <i class="fas fa-play me-1"></i>
                                        <span class="d-none d-sm-inline">ทดสอบประสิทธิภาพ</span>
                                        <span class="d-sm-none">ทดสอบ</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
            
            <!-- Performance Metrics -->
            <div class="performance-metrics mt-4">
                <h6>Performance Metrics</h6>
                <div class="row g-3">
                    <div class="col-md-3">
                        <div class="metric-card">
                            <div class="metric-value" id="responseTime">245ms</div>
                            <div class="metric-label">Response Time</div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="metric-card">
                            <div class="metric-value" id="memoryUsage">128MB</div>
                            <div class="metric-label">Memory Usage</div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="metric-card">
                            <div class="metric-value" id="cacheHitRate">85%</div>
                            <div class="metric-label">Cache Hit Rate</div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="metric-card">
                            <div class="metric-value" id="activeConnections">12</div>
                            <div class="metric-label">Active Connections</div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="metric-card">
                            <div class="metric-value" id="bufferPoolHitRate">95.5%</div>
                            <div class="metric-label">Buffer Pool Hit Rate</div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="metric-card">
                            <div class="metric-value" id="tableLockWaitRatio">2.1%</div>
                            <div class="metric-label">Table Lock Wait Ratio</div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="metric-card">
                            <div class="metric-value" id="tmpTableRatio">15.3%</div>
                            <div class="metric-label">Temporary Table Ratio</div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="metric-card">
                            <div class="metric-value" id="totalQueries">1000</div>
                            <div class="metric-label">Total Queries</div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Performance Analysis Tables -->
            <div class="performance-analysis mt-4">
                <div class="row g-4">
                    <!-- Slow Queries -->
                    <div class="col-lg-6">
                        <div class="analysis-card">
                            <div class="card-header">
                                <h6><i class="fas fa-clock me-2"></i>Slow Queries</h6>
                                <button class="btn btn-sm btn-outline-primary" onclick="refreshSlowQueries()">
                                    <i class="fas fa-sync-alt"></i>
                                </button>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-sm">
                                        <thead>
                                            <tr>
                                                <th>Query</th>
                                                <th>Time (ms)</th>
                                                <th>Count</th>
                                            </tr>
                                        </thead>
                                        <tbody id="slowQueriesTable">
                                            <tr>
                                                <td colspan="3" class="text-center text-muted">
                                                    <i class="fas fa-info-circle me-2"></i>
                                                    ไม่มี Slow Queries
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Duplicate Queries -->
                    <div class="col-lg-6">
                        <div class="analysis-card">
                            <div class="card-header">
                                <h6><i class="fas fa-copy me-2"></i>Duplicate Queries</h6>
                                <button class="btn btn-sm btn-outline-primary" onclick="refreshDuplicateQueries()">
                                    <i class="fas fa-sync-alt"></i>
                                </button>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-sm">
                                        <thead>
                                            <tr>
                                                <th>Query</th>
                                                <th>Count</th>
                                                <th>Impact</th>
                                            </tr>
                                        </thead>
                                        <tbody id="duplicateQueriesTable">
                                            <tr>
                                                <td colspan="3" class="text-center text-muted">
                                                    <i class="fas fa-info-circle me-2"></i>
                                                    ไม่มี Duplicate Queries
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Table Statistics -->
                    <div class="col-lg-6">
                        <div class="analysis-card">
                            <div class="card-header">
                                <h6><i class="fas fa-table me-2"></i>Table Statistics</h6>
                                <button class="btn btn-sm btn-outline-primary" onclick="refreshTableStatistics()">
                                    <i class="fas fa-sync-alt"></i>
                                </button>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-sm">
                                        <thead>
                                            <tr>
                                                <th>Table</th>
                                                <th>Rows</th>
                                                <th>Size (MB)</th>
                                                <th>Engine</th>
                                            </tr>
                                        </thead>
                                        <tbody id="tableStatisticsTable">
                                            <tr>
                                                <td colspan="4" class="text-center text-muted">
                                                    <i class="fas fa-info-circle me-2"></i>
                                                    กำลังโหลดข้อมูลตาราง...
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Index Statistics -->
                    <div class="col-lg-6">
                        <div class="analysis-card">
                            <div class="card-header">
                                <h6><i class="fas fa-search me-2"></i>Index Statistics</h6>
                                <button class="btn btn-sm btn-outline-primary" onclick="refreshIndexStatistics()">
                                    <i class="fas fa-sync-alt"></i>
                                </button>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-sm">
                                        <thead>
                                            <tr>
                                                <th>Table</th>
                                                <th>Index</th>
                                                <th>Usage</th>
                                                <th>Type</th>
                                            </tr>
                                        </thead>
                                        <tbody id="indexStatisticsTable">
                                            <tr>
                                                <td colspan="4" class="text-center text-muted">
                                                    <i class="fas fa-info-circle me-2"></i>
                                                    กำลังโหลดข้อมูล index...
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
