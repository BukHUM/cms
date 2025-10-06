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
                        <select class="form-select" id="backupFrequency">
                            <option value="daily" selected>รายวัน</option>
                            <option value="weekly">รายสัปดาห์</option>
                            <option value="monthly">รายเดือน</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="backupTime" class="form-label">เวลาสำรองข้อมูล</label>
                        <input type="time" class="form-control" id="backupTime" value="02:00">
                    </div>
                    <div class="col-md-6">
                        <label for="backupRetention" class="form-label">เก็บไฟล์สำรอง (วัน)</label>
                        <input type="number" class="form-control" id="backupRetention" value="30">
                    </div>
                    <div class="col-md-6">
                        <label for="backupLocation" class="form-label">ตำแหน่งเก็บไฟล์สำรอง</label>
                        <select class="form-select" id="backupLocation">
                            <option value="local" selected>Local Storage</option>
                            <option value="s3">Amazon S3</option>
                            <option value="google">Google Drive</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="backupEnabled" class="form-label">เปิดใช้งานการสำรองข้อมูลอัตโนมัติ</label>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="backupEnabled" checked>
                            <label class="form-check-label" for="backupEnabled" id="backupEnabledLabel">
                                เปิดใช้งาน
                            </label>
                        </div>
                    </div>
                </div>
                <!-- Desktop Actions -->
                <div class="mt-4 d-none d-md-block">
                    <button type="button" class="btn btn-warning me-2" onclick="createBackup()">
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
                            <button type="button" class="btn btn-warning w-100" onclick="createBackup()">
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
                                <th>ขนาดไฟล์</th>
                                <th>สถานะ</th>
                                <th>การดำเนินการ</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>2024-01-25 02:00</td>
                                <td>15.2 MB</td>
                                <td><span class="badge bg-success">สำเร็จ</span></td>
                                <td>
                                    <button class="btn btn-sm btn-outline-primary">ดาวน์โหลด</button>
                                    <button class="btn btn-sm btn-outline-danger">ลบ</button>
                                </td>
                            </tr>
                            <tr>
                                <td>2024-01-24 02:00</td>
                                <td>14.8 MB</td>
                                <td><span class="badge bg-success">สำเร็จ</span></td>
                                <td>
                                    <button class="btn btn-sm btn-outline-primary">ดาวน์โหลด</button>
                                    <button class="btn btn-sm btn-outline-danger">ลบ</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
