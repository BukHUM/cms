<!-- Security Settings -->
<div class="tab-pane fade" id="security" role="tabpanel">
    <div class="settings-card">
        <div class="card-header">
            <h5>การตั้งค่าความปลอดภัย</h5>
        </div>
        <div class="card-body">
            <form id="securitySettingsForm">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="sessionLifetime" class="form-label">ระยะเวลา Session (นาที)</label>
                        <input type="number" class="form-control" id="sessionLifetime" value="120">
                    </div>
                    <div class="col-md-6">
                        <label for="maxLoginAttempts" class="form-label">จำนวนครั้งการเข้าสู่ระบบสูงสุด</label>
                        <input type="number" class="form-control" id="maxLoginAttempts" value="5">
                    </div>
                    <div class="col-md-6">
                        <label for="passwordMinLength" class="form-label">ความยาวรหัสผ่านขั้นต่ำ</label>
                        <input type="number" class="form-control" id="passwordMinLength" value="8">
                    </div>
                    <div class="col-md-6">
                        <label for="requireSpecialChars" class="form-label">ต้องมีอักขระพิเศษ</label>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="requireSpecialChars" checked>
                            <label class="form-check-label" for="requireSpecialChars" id="requireSpecialCharsLabel">
                                เปิดใช้งาน
                            </label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label for="twoFactorAuth" class="form-label">Two-Factor Authentication</label>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="twoFactorAuth">
                            <label class="form-check-label" for="twoFactorAuth" id="twoFactorAuthLabel">
                                เปิดใช้งาน
                            </label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label for="ipWhitelist" class="form-label">IP Whitelist</label>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="ipWhitelist">
                            <label class="form-check-label" for="ipWhitelist" id="ipWhitelistLabel">
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
