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
                        <input type="text" class="form-control" id="siteName" value="">
                        <div class="form-text">ชื่อที่แสดงในหน้าเว็บไซต์และในแท็บเบราว์เซอร์</div>
                    </div>
                    <div class="col-md-6">
                        <label for="siteUrl" class="form-label">URL เว็บไซต์</label>
                        <input type="url" class="form-control" id="siteUrl" value="">
                        <div class="form-text">URL หลักของเว็บไซต์ (ต้องมี http:// หรือ https://)</div>
                    </div>
                    <div class="col-md-6">
                        <label for="timezone" class="form-label">เขตเวลา</label>
                        <select class="form-select" id="timezone">
                            <option value="Asia/Bangkok" selected>Asia/Bangkok</option>
                            <option value="UTC">UTC</option>
                            <option value="America/New_York">America/New_York</option>
                        </select>
                        <div class="form-text">เขตเวลาที่ใช้ในการแสดงผลวันที่และเวลา</div>
                    </div>
                    <div class="col-md-6">
                        <label for="language" class="form-label">ภาษา</label>
                        <select class="form-select" id="language">
                            <option value="th" selected>ไทย</option>
                            <option value="en">English</option>
                        </select>
                        <div class="form-text">ภาษาหลักที่ใช้ในระบบ</div>
                    </div>
                    <div class="col-md-6">
                        <label for="maintenanceMode" class="form-label">โหมดบำรุงรักษา</label>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="maintenanceMode">
                            <label class="form-check-label" for="maintenanceMode" id="maintenanceModeLabel">
                                เปิดใช้งานโหมดบำรุงรักษา
                            </label>
                        </div>
                        <div class="form-text">เมื่อเปิดใช้งาน ผู้ใช้ทั่วไปจะไม่สามารถเข้าถึงเว็บไซต์ได้</div>
                    </div>
                    <div class="col-md-6">
                        <label for="debugLevel" class="form-label">ระดับ Debug</label>
                        <select class="form-select" id="debugLevel">
                            <option value="off">ปิดใช้งาน</option>
                            <option value="minimal">ขั้นต่ำ - แสดงข้อผิดพลาดพื้นฐาน</option>
                            <option value="standard" selected>มาตรฐาน - แสดงข้อผิดพลาดและข้อมูล debug</option>
                            <option value="verbose">ละเอียด - แสดงข้อมูล debug ครบถ้วน</option>
                            <option value="development">พัฒนา - แสดงข้อมูลทั้งหมดสำหรับการพัฒนา</option>
                        </select>
                        <div class="form-text">เลือกระดับความละเอียดของข้อมูล debug ที่จะแสดง</div>
                    </div>
                    <div class="col-md-6">
                        <label for="debugBar" class="form-label">Debug Bar</label>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="debugBar" checked>
                            <label class="form-check-label" for="debugBar" id="debugBarLabel">
                                เปิดใช้งาน Debug Bar
                            </label>
                        </div>
                        <div class="form-text">แสดงแถบ debug ที่ด้านล่างของหน้าเว็บไซต์</div>
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
