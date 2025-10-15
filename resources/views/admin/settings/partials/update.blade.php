<!-- Update Settings -->
<div class="tab-pane fade" id="update" role="tabpanel">
    <div class="settings-card">
        <div class="card-header">
            <h5>การตั้งค่าอัปเดตระบบ</h5>
        </div>
        <div class="card-body">
            <form id="updateSettingsForm">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="autoUpdate" class="form-label">เปิดใช้งานการอัปเดตอัตโนมัติ</label>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="autoUpdate" checked>
                            <label class="form-check-label" for="autoUpdate" id="autoUpdateLabel">
                                เปิดใช้งาน
                            </label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label for="updateChannel" class="form-label">ช่องทางการอัปเดต</label>
                        <select class="form-select" id="updateChannel">
                            <option value="stable" selected>Stable (แนะนำ)</option>
                            <option value="beta">Beta</option>
                            <option value="dev">Development</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="backupBeforeUpdate" class="form-label">สำรองข้อมูลก่อนอัปเดต</label>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="backupBeforeUpdate" checked>
                            <label class="form-check-label" for="backupBeforeUpdate" id="backupBeforeUpdateLabel">
                                เปิดใช้งาน
                            </label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label for="notifyOnUpdate" class="form-label">แจ้งเตือนเมื่อมีการอัปเดต</label>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="notifyOnUpdate" checked>
                            <label class="form-check-label" for="notifyOnUpdate" id="notifyOnUpdateLabel">
                                เปิดใช้งาน
                            </label>
                        </div>
                    </div>
                </div>
                
                <!-- Desktop Actions -->
                <div class="mt-4 d-none d-md-block">
                    <button type="button" class="btn btn-info me-2" onclick="checkForUpdates()">
                        <i class="fas fa-search me-2"></i>
                        ตรวจสอบการอัปเดต
                    </button>
                    <button type="button" class="btn btn-success me-2" onclick="updateComposer()" id="updateComposerBtn" disabled>
                        <i class="fas fa-download me-2"></i>
                        อัปเดต Composer
                    </button>
                    <button type="button" class="btn btn-warning me-2" onclick="updateLaravel()" id="updateLaravelBtn" disabled>
                        <i class="fas fa-sync-alt me-2"></i>
                        อัปเดต Laravel
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
                                <div class="col-4">
                                    <button type="button" class="btn btn-info w-100" onclick="checkForUpdates()">
                                        <i class="fas fa-search me-1"></i>
                                        <span class="d-none d-sm-inline">ตรวจสอบ</span>
                                        <span class="d-sm-none">เช็ค</span>
                                    </button>
                                </div>
                                <div class="col-4">
                                    <button type="button" class="btn btn-success w-100" onclick="updateComposer()" id="updateComposerBtnMobile" disabled>
                                        <i class="fas fa-download me-1"></i>
                                        <span class="d-none d-sm-inline">Composer</span>
                                        <span class="d-sm-none">Comp</span>
                                    </button>
                                </div>
                                <div class="col-4">
                                    <button type="button" class="btn btn-warning w-100" onclick="updateLaravel()" id="updateLaravelBtnMobile" disabled>
                                        <i class="fas fa-sync-alt me-1"></i>
                                        <span class="d-none d-sm-inline">Laravel</span>
                                        <span class="d-sm-none">Lar</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
            
            <!-- Update Status -->
            <div class="alert alert-info mt-4" id="updateStatusAlert" style="display: none;">
                <i class="fas fa-info-circle me-2"></i>
                <span id="updateStatusText">กำลังตรวจสอบการอัปเดต...</span>
            </div>
            
            <!-- System Information -->
            <div class="mt-4">
                <h6>ข้อมูลระบบปัจจุบัน</h6>
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>รายการ</th>
                                <th>ค่า</th>
                                <th>สถานะ</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><i class="fas fa-code me-2"></i>Laravel Version</td>
                                <td id="currentVersion">{{ app()->version() }}</td>
                                <td><span class="badge bg-success">ล่าสุด</span></td>
                            </tr>
                            <tr>
                                <td><i class="fas fa-calendar-alt me-2"></i>วันที่ติดตั้ง</td>
                                <td id="installDate">{{ date('d/m/Y H:i:s') }}</td>
                                <td><span class="badge bg-info">ปกติ</span></td>
                            </tr>
                            <tr>
                                <td><i class="fas fa-server me-2"></i>PHP Version</td>
                                <td id="phpVersion">{{ PHP_VERSION }}</td>
                                <td><span class="badge bg-success">รองรับ</span></td>
                            </tr>
                            <tr>
                                <td><i class="fas fa-memory me-2"></i>Memory Limit</td>
                                <td id="memoryLimit">{{ ini_get('memory_limit') }}</td>
                                <td><span class="badge bg-success">เพียงพอ</span></td>
                            </tr>
                            <tr>
                                <td><i class="fas fa-hdd me-2"></i>Disk Space</td>
                                <td id="diskSpace">กำลังตรวจสอบ...</td>
                                <td><span class="badge bg-warning">กำลังตรวจสอบ</span></td>
                            </tr>
                            <tr>
                                <td><i class="fas fa-box me-2"></i>Composer</td>
                                <td id="composerVersion">กำลังตรวจสอบ...</td>
                                <td><span class="badge bg-warning">กำลังตรวจสอบ</span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            
            <!-- Update Log -->
            <div class="mt-4">
                <h6>บันทึกการอัปเดต</h6>
                <div class="log-container">
                    <pre id="updateLog" class="log-content">พร้อมสำหรับการอัปเดต...</pre>
                </div>
            </div>
            
            <!-- SweetAlert2 Test Buttons (for development) -->
            <div class="mt-4">
                <h6>ทดสอบ SweetAlert2</h6>
                <div class="row g-2">
                    <div class="col-md-3 col-6">
                        <button type="button" class="btn btn-sm btn-outline-success w-100" onclick="testSweetAlert('success')">
                            <i class="fas fa-check me-1"></i>Success
                        </button>
                    </div>
                    <div class="col-md-3 col-6">
                        <button type="button" class="btn btn-sm btn-outline-warning w-100" onclick="testSweetAlert('warning')">
                            <i class="fas fa-exclamation-triangle me-1"></i>Warning
                        </button>
                    </div>
                    <div class="col-md-3 col-6">
                        <button type="button" class="btn btn-sm btn-outline-info w-100" onclick="testSweetAlert('info')">
                            <i class="fas fa-info-circle me-1"></i>Info
                        </button>
                    </div>
                    <div class="col-md-3 col-6">
                        <button type="button" class="btn btn-sm btn-outline-danger w-100" onclick="testSweetAlert('error')">
                            <i class="fas fa-times-circle me-1"></i>Error
                        </button>
                    </div>
                </div>
            </div>
            
            <!-- Backup Warning -->
            <div class="alert alert-warning mt-4">
                <i class="fas fa-exclamation-triangle me-2"></i>
                <strong>คำเตือน:</strong> ก่อนทำการอัปเดต กรุณาสร้างการสำรองข้อมูลก่อน เพื่อป้องกันการสูญหายของข้อมูล
            </div>
        </div>
    </div>
</div>
