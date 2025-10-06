<!-- Email Settings -->
<div class="tab-pane fade" id="email" role="tabpanel">
    <div class="settings-card">
        <div class="card-header">
            <h5>การตั้งค่าอีเมล</h5>
        </div>
        <div class="card-body">
            <form id="emailSettingsForm">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="mailDriver" class="form-label">Mail Driver</label>
                        <select class="form-select" id="mailDriver">
                            <option value="smtp" selected>SMTP</option>
                            <option value="mailgun">Mailgun</option>
                            <option value="ses">Amazon SES</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="mailHost" class="form-label">Mail Host</label>
                        <input type="text" class="form-control" id="mailHost" value="smtp.gmail.com">
                    </div>
                    <div class="col-md-6">
                        <label for="mailPort" class="form-label">Mail Port</label>
                        <input type="number" class="form-control" id="mailPort" value="587">
                    </div>
                    <div class="col-md-6">
                        <label for="mailUsername" class="form-label">Username</label>
                        <input type="text" class="form-control" id="mailUsername">
                    </div>
                    <div class="col-md-6">
                        <label for="mailPassword" class="form-label">Password</label>
                        <input type="password" class="form-control" id="mailPassword">
                    </div>
                    <div class="col-md-6">
                        <label for="mailEncryption" class="form-label">Encryption</label>
                        <select class="form-select" id="mailEncryption">
                            <option value="tls" selected>TLS</option>
                            <option value="ssl">SSL</option>
                            <option value="">None</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="mailFromAddress" class="form-label">From Address</label>
                        <input type="email" class="form-control" id="mailFromAddress" value="noreply@example.com">
                    </div>
                    <div class="col-md-6">
                        <label for="mailFromName" class="form-label">From Name</label>
                        <input type="text" class="form-control" id="mailFromName" value="{{ config('app.name') }}">
                    </div>
                    <div class="col-md-6">
                        <label for="mailEnabled" class="form-label">เปิดใช้งานการส่งอีเมล</label>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="mailEnabled" checked>
                            <label class="form-check-label" for="mailEnabled" id="mailEnabledLabel">
                                เปิดใช้งาน
                            </label>
                        </div>
                    </div>
                </div>
                <!-- Desktop Actions -->
                <div class="mt-4 d-none d-md-block">
                    <button type="button" class="btn btn-info me-2" onclick="testEmail()">
                        <i class="fas fa-paper-plane me-2"></i>
                        ทดสอบการส่งอีเมล
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
                            <button type="button" class="btn btn-info w-100" onclick="testEmail()">
                                <i class="fas fa-paper-plane me-1"></i>
                                <span class="d-none d-sm-inline">ทดสอบการส่งอีเมล</span>
                                <span class="d-sm-none">ทดสอบอีเมล</span>
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
