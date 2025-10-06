<!-- Audit Log Settings -->
<div class="tab-pane fade" id="audit" role="tabpanel">
    <div class="settings-card">
        <div class="card-header">
            <h5>การตั้งค่า Audit Log</h5>
        </div>
        <div class="card-body">
            <form id="auditSettingsForm">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="auditEnabled" class="form-label">เปิดใช้งาน Audit Log</label>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="auditEnabled" checked>
                            <label class="form-check-label" for="auditEnabled" id="auditEnabledLabel">
                                บันทึกการใช้งานระบบ
                            </label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label for="auditRetention" class="form-label">เก็บข้อมูล (วัน)</label>
                        <input type="number" class="form-control" id="auditRetention" value="90" min="7" max="365">
                    </div>
                    <div class="col-md-6">
                        <label for="auditLevel" class="form-label">ระดับการบันทึก</label>
                        <select class="form-select" id="auditLevel">
                            <option value="basic" selected>พื้นฐาน (Login, Logout, ข้อมูลสำคัญ)</option>
                            <option value="detailed">ละเอียด (ทุกการกระทำ)</option>
                            <option value="comprehensive">ครบถ้วน (รวมการดูข้อมูล)</option>
                        </select>
                    </div>
                </div>
                <!-- Desktop Actions -->
                <div class="mt-4 d-none d-md-block">
                    <button type="button" class="btn btn-warning me-2" onclick="exportAuditLogs()">
                        <i class="fas fa-download me-2"></i>
                        ส่งออก Log
                    </button>
                    <button type="button" class="btn btn-danger me-2" onclick="clearAuditLogs()">
                        <i class="fas fa-trash me-2"></i>
                        ล้าง Log
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
                                    <button type="button" class="btn btn-warning w-100" onclick="exportAuditLogs()">
                                        <i class="fas fa-download me-1"></i>
                                        <span class="d-none d-sm-inline">ส่งออก Log</span>
                                        <span class="d-sm-none">ส่งออก</span>
                                    </button>
                                </div>
                                <div class="col-6">
                                    <button type="button" class="btn btn-danger w-100" onclick="clearAuditLogs()">
                                        <i class="fas fa-trash me-1"></i>
                                        <span class="d-none d-sm-inline">ล้าง Log</span>
                                        <span class="d-sm-none">ล้าง</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
            
            <!-- Recent Audit Logs -->
            <div class="audit-logs-table mt-4">
                <h6>Audit Log ล่าสุด</h6>
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>เวลา</th>
                                <th>ผู้ใช้</th>
                                <th>การกระทำ</th>
                                <th>IP Address</th>
                                <th>สถานะ</th>
                            </tr>
                        </thead>
                        <tbody id="auditLogsTable">
                            <tr>
                                <td colspan="5" class="text-center text-muted">
                                    <i class="fas fa-spinner fa-spin me-2"></i>
                                    กำลังโหลดข้อมูล...
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
