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
            
            <!-- Update Status Alert -->
            <div class="update-status-container mt-5" id="updateStatusContainer" style="display: none;">
                <div class="alert alert-success alert-dismissible fade show shadow-sm border-0" id="updateStatusAlert">
                    <div class="d-flex align-items-center">
                        <div class="alert-icon me-3">
                            <i class="fas fa-check-circle fa-lg text-success"></i>
                        </div>
                        <div class="alert-content flex-grow-1">
                            <h6 class="alert-title mb-1 fw-semibold">พบการอัปเดตใหม่!</h6>
                            <p class="alert-message mb-0 text-muted" id="updateStatusText">ระบบพบเวอร์ชันใหม่ที่พร้อมใช้งาน</p>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                </div>
                
                <!-- Update Progress Bar -->
                <div class="update-progress mt-3" id="updateProgress" style="display: none;">
                    <div class="progress" style="height: 8px;">
                        <div class="progress-bar progress-bar-striped progress-bar-animated bg-success" 
                             role="progressbar" style="width: 0%" id="updateProgressBar"></div>
                    </div>
                    <small class="text-muted mt-2 d-block" id="updateProgressText">กำลังเตรียมการอัปเดต...</small>
                </div>
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
                                <th>การอัปเดต</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><i class="fas fa-code me-2"></i>Laravel Version</td>
                                <td id="currentVersion">{{ app()->version() }}</td>
                                <td><span class="badge bg-success">ล่าสุด</span></td>
                                <td id="laravelUpdateStatus">
                                    <span class="badge bg-secondary">ยังไม่ตรวจสอบ</span>
                                </td>
                            </tr>
                            <tr>
                                <td><i class="fas fa-box me-2"></i>Composer</td>
                                <td id="composerVersion">กำลังตรวจสอบ...</td>
                                <td><span class="badge bg-warning">กำลังตรวจสอบ</span></td>
                                <td id="composerUpdateStatus">
                                    <span class="badge bg-secondary">ยังไม่ตรวจสอบ</span>
                                </td>
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
            
            <!-- Backup Warning -->
            <div class="alert alert-warning mt-4">
                <i class="fas fa-exclamation-triangle me-2"></i>
                <strong>คำเตือน:</strong> ก่อนทำการอัปเดต กรุณาสร้างการสำรองข้อมูลก่อน เพื่อป้องกันการสูญหายของข้อมูล
            </div>
        </div>
    </div>
</div>
