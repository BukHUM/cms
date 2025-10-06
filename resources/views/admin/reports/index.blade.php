@extends('layouts.admin')

@section('title', 'รายงาน')
@section('page-title', 'รายงาน')
@section('page-subtitle', 'รายงานและสถิติการใช้งานระบบ')

@section('content')
<!-- Report Filters -->
<div class="card mb-4">
    <div class="card-body">
        <form id="reportFilters">
            <div class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label for="reportType" class="form-label">ประเภทรายงาน</label>
                    <select class="form-select" id="reportType">
                        <option value="users">รายงานผู้ใช้</option>
                        <option value="activity">รายงานกิจกรรม</option>
                        <option value="performance">รายงานประสิทธิภาพ</option>
                        <option value="errors">รายงานข้อผิดพลาด</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="dateFrom" class="form-label">วันที่เริ่มต้น</label>
                    <input type="date" class="form-control" id="dateFrom" value="{{ date('Y-m-01') }}">
                </div>
                <div class="col-md-3">
                    <label for="dateTo" class="form-label">วันที่สิ้นสุด</label>
                    <input type="date" class="form-control" id="dateTo" value="{{ date('Y-m-d') }}">
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-search me-2"></i>
                        สร้างรายงาน
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Report Charts -->
<div class="row mb-4">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">กราฟการใช้งาน</h5>
            </div>
            <div class="card-body">
                <canvas id="usageChart" height="100"></canvas>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">สถิติสรุป</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <div class="d-flex justify-content-between">
                        <span>ผู้ใช้ทั้งหมด</span>
                        <span class="fw-bold text-primary">1,234</span>
                    </div>
                </div>
                <div class="mb-3">
                    <div class="d-flex justify-content-between">
                        <span>ผู้ใช้ใหม่</span>
                        <span class="fw-bold text-success">45</span>
                    </div>
                </div>
                <div class="mb-3">
                    <div class="d-flex justify-content-between">
                        <span>การเข้าชม</span>
                        <span class="fw-bold text-info">8,765</span>
                    </div>
                </div>
                <div class="mb-3">
                    <div class="d-flex justify-content-between">
                        <span>ข้อผิดพลาด</span>
                        <span class="fw-bold text-warning">12</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Detailed Reports -->
<div class="row">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">รายงานผู้ใช้</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>วันที่</th>
                                <th>ผู้ใช้ใหม่</th>
                                <th>ผู้ใช้ที่ใช้งาน</th>
                                <th>ผู้ใช้ที่ไม่ใช้งาน</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>2024-01-25</td>
                                <td class="text-success">5</td>
                                <td class="text-primary">1,200</td>
                                <td class="text-muted">29</td>
                            </tr>
                            <tr>
                                <td>2024-01-24</td>
                                <td class="text-success">3</td>
                                <td class="text-primary">1,195</td>
                                <td class="text-muted">31</td>
                            </tr>
                            <tr>
                                <td>2024-01-23</td>
                                <td class="text-success">7</td>
                                <td class="text-primary">1,192</td>
                                <td class="text-muted">28</td>
                            </tr>
                            <tr>
                                <td>2024-01-22</td>
                                <td class="text-success">4</td>
                                <td class="text-primary">1,185</td>
                                <td class="text-muted">30</td>
                            </tr>
                            <tr>
                                <td>2024-01-21</td>
                                <td class="text-success">6</td>
                                <td class="text-primary">1,181</td>
                                <td class="text-muted">32</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">รายงานประสิทธิภาพ</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <div class="d-flex justify-content-between mb-1">
                        <span>เวลาตอบสนองเฉลี่ย</span>
                        <span>245ms</span>
                    </div>
                    <div class="progress" style="height: 8px;">
                        <div class="progress-bar bg-success" style="width: 85%"></div>
                    </div>
                </div>
                
                <div class="mb-3">
                    <div class="d-flex justify-content-between mb-1">
                        <span>อัพไทม์</span>
                        <span>99.9%</span>
                    </div>
                    <div class="progress" style="height: 8px;">
                        <div class="progress-bar bg-success" style="width: 99%"></div>
                    </div>
                </div>
                
                <div class="mb-3">
                    <div class="d-flex justify-content-between mb-1">
                        <span>การใช้ CPU</span>
                        <span>45%</span>
                    </div>
                    <div class="progress" style="height: 8px;">
                        <div class="progress-bar bg-info" style="width: 45%"></div>
                    </div>
                </div>
                
                <div class="mb-3">
                    <div class="d-flex justify-content-between mb-1">
                        <span>การใช้ RAM</span>
                        <span>68%</span>
                    </div>
                    <div class="progress" style="height: 8px;">
                        <div class="progress-bar bg-warning" style="width: 68%"></div>
                    </div>
                </div>
                
                <div class="mb-3">
                    <div class="d-flex justify-content-between mb-1">
                        <span>พื้นที่เก็บข้อมูล</span>
                        <span>75%</span>
                    </div>
                    <div class="progress" style="height: 8px;">
                        <div class="progress-bar bg-warning" style="width: 75%"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Export Options -->
<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">ส่งออกรายงาน</h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-3">
                        <button class="btn btn-success w-100" onclick="exportReport('pdf')">
                            <i class="fas fa-file-pdf me-2"></i>
                            ส่งออกเป็น PDF
                        </button>
                    </div>
                    <div class="col-md-3">
                        <button class="btn btn-primary w-100" onclick="exportReport('excel')">
                            <i class="fas fa-file-excel me-2"></i>
                            ส่งออกเป็น Excel
                        </button>
                    </div>
                    <div class="col-md-3">
                        <button class="btn btn-info w-100" onclick="exportReport('csv')">
                            <i class="fas fa-file-csv me-2"></i>
                            ส่งออกเป็น CSV
                        </button>
                    </div>
                    <div class="col-md-3">
                        <button class="btn btn-warning w-100" onclick="printReport()">
                            <i class="fas fa-print me-2"></i>
                            พิมพ์รายงาน
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Usage Chart
const ctx = document.getElementById('usageChart').getContext('2d');
const usageChart = new Chart(ctx, {
    type: 'line',
    data: {
        labels: ['1/21', '1/22', '1/23', '1/24', '1/25'],
        datasets: [{
            label: 'ผู้ใช้ที่ใช้งาน',
            data: [1181, 1185, 1192, 1195, 1200],
            borderColor: 'rgb(75, 192, 192)',
            backgroundColor: 'rgba(75, 192, 192, 0.2)',
            tension: 0.1
        }, {
            label: 'ผู้ใช้ใหม่',
            data: [6, 4, 7, 3, 5],
            borderColor: 'rgb(255, 99, 132)',
            backgroundColor: 'rgba(255, 99, 132, 0.2)',
            tension: 0.1
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            title: {
                display: true,
                text: 'สถิติการใช้งานรายวัน'
            }
        },
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});

// Report filters form
document.getElementById('reportFilters').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const reportType = document.getElementById('reportType').value;
    const dateFrom = document.getElementById('dateFrom').value;
    const dateTo = document.getElementById('dateTo').value;
    
    console.log('Generating report:', { reportType, dateFrom, dateTo });
    SwalHelper.loading('กำลังสร้างรายงาน...');
    
    // Simulate report generation
    setTimeout(() => {
        SwalHelper.close();
        SwalHelper.success('สร้างรายงานสำเร็จ!');
    }, 2000);
});

// Export functions
function exportReport(format) {
    console.log('Exporting report as:', format);
    SwalHelper.loading('กำลังส่งออกรายงานเป็น ' + format.toUpperCase() + '...');
    
    // Simulate export process
    setTimeout(() => {
        SwalHelper.close();
        SwalHelper.success('ส่งออกรายงานสำเร็จ!');
    }, 1500);
}

function printReport() {
    console.log('Printing report...');
    window.print();
}
</script>
@endpush
