<!-- Security Settings -->
<div class="tab-pane fade" id="security" role="tabpanel">
    <div class="settings-card">
        <div class="card-header">
            <h5>การตั้งค่าความปลอดภัย</h5>
        </div>
        <div class="card-body">
            <form id="securitySettingsForm">
                @csrf
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="sessionLifetime" class="form-label">ระยะเวลา Session (นาที)</label>
                        <input type="number" class="form-control" id="sessionLifetime" name="sessionLifetime" 
                               value="120" min="5" max="1440" required>
                        <div class="form-text">ช่วงเวลา 5-1440 นาที</div>
                    </div>
                    <div class="col-md-6">
                        <label for="maxLoginAttempts" class="form-label">จำนวนครั้งการเข้าสู่ระบบสูงสุด</label>
                        <input type="number" class="form-control" id="maxLoginAttempts" name="maxLoginAttempts" 
                               value="5" min="3" max="20" required>
                        <div class="form-text">ช่วงเวลา 3-20 ครั้ง</div>
                    </div>
                    <div class="col-md-6">
                        <label for="passwordMinLength" class="form-label">ความยาวรหัสผ่านขั้นต่ำ</label>
                        <input type="number" class="form-control" id="passwordMinLength" name="passwordMinLength" 
                               value="8" min="6" max="50" required>
                        <div class="form-text">ช่วงเวลา 6-50 ตัวอักษร</div>
                    </div>
                    <div class="col-md-6">
                        <label for="requireSpecialChars" class="form-label">ต้องมีอักขระพิเศษ</label>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="requireSpecialChars" 
                                   name="requireSpecialChars" checked>
                            <label class="form-check-label" for="requireSpecialChars" id="requireSpecialCharsLabel">
                                เปิดใช้งาน
                            </label>
                        </div>
                        <div class="form-text">รหัสผ่านต้องมีอักขระพิเศษ (!@#$%^&*)</div>
                    </div>
                    <div class="col-md-6">
                        <label for="twoFactorAuth" class="form-label">Two-Factor Authentication</label>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="twoFactorAuth" 
                                   name="twoFactorAuth">
                            <label class="form-check-label" for="twoFactorAuth" id="twoFactorAuthLabel">
                                ปิดใช้งาน
                            </label>
                        </div>
                        <div class="form-text">เพิ่มความปลอดภัยด้วยการยืนยันตัวตน 2 ขั้นตอน</div>
                    </div>
                    <div class="col-md-6">
                        <label for="ipWhitelist" class="form-label">IP Whitelist</label>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="ipWhitelist" 
                                   name="ipWhitelist">
                            <label class="form-check-label" for="ipWhitelist" id="ipWhitelistLabel">
                                ปิดใช้งาน
                            </label>
                        </div>
                        <div class="form-text">จำกัดการเข้าถึงจาก IP ที่อนุญาตเท่านั้น</div>
                    </div>
                </div>
                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>
                        บันทึกการตั้งค่า
                    </button>
                    <button type="button" class="btn btn-secondary ms-2" onclick="location.reload()">
                        <i class="fas fa-refresh me-2"></i>
                        รีเซ็ต
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script src="{{ asset('js/settings/settings-security.js') }}"></script>
@endpush
