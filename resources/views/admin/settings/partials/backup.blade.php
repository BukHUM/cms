<!-- Backup Settings -->
<div class="tab-pane fade" id="backup" role="tabpanel">
    <div class="settings-card">
        <div class="card-header">
            <h5>การตั้งค่าสำรองข้อมูล</h5>
        </div>
        <div class="card-body">
            <form id="backupSettingsForm">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="backupFrequency" class="form-label">ความถี่การสำรองข้อมูล</label>
                        <select class="form-select" id="backupFrequency" name="backupFrequency">
                            <option value="daily" selected>รายวัน</option>
                            <option value="weekly">รายสัปดาห์</option>
                            <option value="monthly">รายเดือน</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="backupTime" class="form-label">เวลาสำรองข้อมูล</label>
                        <input type="time" class="form-control" id="backupTime" name="backupTime" value="02:00">
                    </div>
                    <div class="col-md-6">
                        <label for="backupRetention" class="form-label">เก็บไฟล์สำรอง</label>
                        <select class="form-select" id="backupRetention" name="backupRetention">
                            <option value="3">3 วัน</option>
                            <option value="7">7 วัน</option>
                            <option value="30" selected>30 วัน</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="backupType" class="form-label">ประเภทการสำรองข้อมูล</label>
                        <select class="form-select" id="backupType" name="backupType">
                            <option value="database" selected>สำรองฐานข้อมูล</option>
                            <option value="files">สำรองไฟล์</option>
                            <option value="both">สำรองทั้งฐานข้อมูลและไฟล์</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="backupLocation" class="form-label">ตำแหน่งเก็บไฟล์สำรอง</label>
                        <select class="form-select" id="backupLocation" name="backupLocation">
                            <option value="local" selected>Local Storage</option>
                            <option value="s3">Amazon S3</option>
                            <option value="google">Google Drive</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="backupEnabled" class="form-label">เปิดใช้งานการสำรองข้อมูลอัตโนมัติ</label>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="backupEnabled" name="backupEnabled" checked>
                            <label class="form-check-label" for="backupEnabled" id="backupEnabledLabel">
                                เปิดใช้งาน
                            </label>
                        </div>
                    </div>
                </div>
                <!-- Desktop Actions -->
                <div class="mt-4 d-none d-md-block">
                    <button type="button" class="btn btn-warning me-2" id="createBackupBtn">
                        <i class="fas fa-database me-2"></i>
                        สร้างสำรองข้อมูลทันที
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
                            <button type="button" class="btn btn-warning w-100" id="createBackupBtnMobile">
                                <i class="fas fa-database me-1"></i>
                                <span class="d-none d-sm-inline">สร้างสำรองข้อมูลทันที</span>
                                <span class="d-sm-none">สร้างสำรองข้อมูล</span>
                            </button>
                        </div>
                    </div>
                </div>
            </form>
            
            <!-- Backup History -->
            <div class="backup-history-table">
                <h6>ประวัติการสำรองข้อมูล</h6>
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>วันที่</th>
                                <th>ประเภท</th>
                                <th>ขนาดไฟล์</th>
                                <th>สถานะ</th>
                                <th>การดำเนินการ</th>
                            </tr>
                        </thead>
                        <tbody id="backupHistoryBody">
                            <tr id="backupLoadingRow" style="display: none;">
                                <td colspan="5" class="text-center">
                                    <div class="spinner-border spinner-border-sm me-2" role="status">
                                        <span class="visually-hidden">กำลังโหลด...</span>
                                    </div>
                                    กำลังโหลดประวัติการสำรองข้อมูล...
                                </td>
                            </tr>
                            <tr id="backupEmptyRow" style="display: none;">
                                <td colspan="5" class="text-center text-muted">
                                    <i class="fas fa-database me-2"></i>
                                    ยังไม่มีประวัติการสำรองข้อมูล
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.action-buttons {
    display: flex;
    gap: 4px;
    align-items: center;
}

.btn-action {
    width: 32px;
    height: 32px;
    border: none;
    border-radius: 6px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.2s ease;
    font-size: 14px;
}

.btn-action:hover:not(:disabled) {
    transform: translateY(-1px);
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.btn-action:disabled {
    opacity: 0.5;
    cursor: not-allowed;
}

.btn-download {
    background-color: #fff3e0;
    color: #f57c00;
}

.btn-download:hover:not(:disabled) {
    background-color: #ffe0b2;
}

.btn-delete {
    background-color: #ffebee;
    color: #d32f2f;
}

.btn-delete:hover:not(:disabled) {
    background-color: #ffcdd2;
}
</style>

<script src="{{ asset('js/settings/settings-backup.js') }}"></script>

