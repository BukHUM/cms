<!-- General Settings -->
<div class="tab-pane fade" id="general" role="tabpanel">
    <div class="settings-card">
        <div class="card-header">
            <h5>การตั้งค่าทั่วไป</h5>
        </div>
        <div class="card-body">
            <form id="generalSettingsForm">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="siteName" class="form-label">ชื่อเว็บไซต์</label>
                        <input type="text" class="form-control" id="siteName" value="{{ config('app.name') }}">
                    </div>
                    <div class="col-md-6">
                        <label for="siteUrl" class="form-label">URL เว็บไซต์</label>
                        <input type="url" class="form-control" id="siteUrl" value="{{ config('app.url') }}">
                    </div>
                    <div class="col-md-6">
                        <label for="timezone" class="form-label">เขตเวลา</label>
                        <select class="form-select" id="timezone">
                            <option value="Asia/Bangkok" selected>Asia/Bangkok</option>
                            <option value="UTC">UTC</option>
                            <option value="America/New_York">America/New_York</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="language" class="form-label">ภาษา</label>
                        <select class="form-select" id="language">
                            <option value="th" selected>ไทย</option>
                            <option value="en">English</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="siteEnabled" class="form-label">เปิดใช้งานเว็บไซต์</label>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="siteEnabled" checked>
                            <label class="form-check-label" for="siteEnabled" id="siteEnabledLabel">
                                เปิดใช้งาน
                            </label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label for="maintenanceMode" class="form-label">โหมดบำรุงรักษา</label>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="maintenanceMode">
                            <label class="form-check-label" for="maintenanceMode" id="maintenanceModeLabel">
                                เปิดใช้งานโหมดบำรุงรักษา
                            </label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label for="debugMode" class="form-label">โหมด Debug</label>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="debugMode" checked>
                            <label class="form-check-label" for="debugMode" id="debugModeLabel">
                                เปิดใช้งานโหมด Debug
                            </label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label for="autoSave" class="form-label">เปิดใช้งานการบันทึกอัตโนมัติ</label>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="autoSave" checked>
                            <label class="form-check-label" for="autoSave" id="autoSaveLabel">
                                เปิดใช้งาน
                            </label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label for="notifications" class="form-label">เปิดใช้งานการแจ้งเตือน</label>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="notifications" checked>
                            <label class="form-check-label" for="notifications" id="notificationsLabel">
                                เปิดใช้งาน
                            </label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label for="analytics" class="form-label">เปิดใช้งานการวิเคราะห์ข้อมูล</label>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="analytics" checked>
                            <label class="form-check-label" for="analytics" id="analyticsLabel">
                                เปิดใช้งาน
                            </label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label for="updates" class="form-label">เปิดใช้งานการอัปเดตอัตโนมัติ</label>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="updates" checked>
                            <label class="form-check-label" for="updates" id="updatesLabel">
                                เปิดใช้งาน
                            </label>
                        </div>
                    </div>
                </div>
                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>
                        บันทึกการตั้งค่า
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
