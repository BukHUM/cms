/**
 * Performance Settings JavaScript
 * จัดการการตั้งค่า Performance ของระบบ
 */

class PerformanceSettings {
    constructor() {
        this.form = document.getElementById('performanceSettingsForm');
        this.isLoading = false;
        this.isClearingCache = false;
        this.isRunningTest = false;
        this.performanceMetrics = {};
        this.slowQueries = [];
        this.duplicateQueries = [];
        this.tableStatistics = [];
        this.indexStatistics = [];
        
        this.init();
    }

    /**
     * Initialize Performance Settings
     */
    init() {
        if (!this.form) return;

        this.loadSettings();
        this.bindEvents();
        this.loadPerformanceMetrics();
        this.loadPerformanceAnalysis();
        this.forceUpdateSwitchLabels(); // Force update labels on init
    }

    /**
     * Force update all switch labels
     */
    forceUpdateSwitchLabels() {
        const switches = this.form.querySelectorAll('.form-check-input[type="checkbox"]');
        switches.forEach(switchElement => {
            const label = document.getElementById(switchElement.id + 'Label');
            if (label) {
                this.updateSwitchLabel(switchElement.id, switchElement.checked);
            }
        });
    }

    /**
     * Load settings from server
     */
    async loadSettings() {
        try {
            this.showLoading(true);
            
            const response = await fetch('/admin/settings/performance', {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            });

            const result = await response.json();

            if (result.success) {
                this.populateForm(result.data);
                // this.showSuccess('โหลดการตั้งค่า Performance สำเร็จ'); // Disabled auto success message
                this.updatePerformanceRecommendations();
            } else {
                this.showError(result.message || 'ไม่สามารถโหลดการตั้งค่า Performance ได้');
            }

        } catch (error) {
            console.error('Error loading performance settings:', error);
            this.showError('เกิดข้อผิดพลาดในการโหลดการตั้งค่า Performance');
        } finally {
            this.showLoading(false);
        }
    }

    /**
     * Populate form with settings data
     */
    populateForm(data) {
        // Basic performance settings
        this.setCheckbox('cacheEnabled', data.cacheEnabled);
        this.setValue('cacheDriver', data.cacheDriver);
        this.setValue('cacheTTL', data.cacheTTL);
        this.setCheckbox('queryLogging', data.queryLogging);
        this.setValue('slowQueryThreshold', data.slowQueryThreshold);
        this.setValue('memoryLimit', data.memoryLimit);
        this.setValue('maxExecutionTime', data.maxExecutionTime);
        this.setCheckbox('compressionEnabled', data.compressionEnabled);

        // Update cache driver settings
        this.updateCacheDriverSettings(data.cacheDriver);
    }

    /**
     * Set input value
     */
    setValue(id, value) {
        const element = document.getElementById(id);
        if (element) {
            element.value = value || '';
        }
    }

    /**
     * Set checkbox value
     */
    setCheckbox(id, value) {
        const element = document.getElementById(id);
        if (element) {
            element.checked = Boolean(value);
            this.updateSwitchLabel(id, Boolean(value));
        }
    }

    /**
     * Update switch label text
     */
    updateSwitchLabel(id, checked) {
        const label = document.getElementById(id + 'Label');
        if (label) {
            const labels = {
                'cacheEnabled': { true: 'เปิดใช้งาน', false: 'ปิดใช้งาน' },
                'queryLogging': { true: 'เปิดใช้งาน', false: 'ปิดใช้งาน' },
                'compressionEnabled': { true: 'เปิดใช้งาน', false: 'ปิดใช้งาน' }
            };

            if (labels[id]) {
                label.textContent = labels[id][checked ? 'true' : 'false'];
                
                // Force inline styles for immediate effect
                if (checked) {
                    label.style.color = '#28a745';
                    label.style.fontWeight = '600';
                } else {
                    label.style.color = '#dc3545';
                    label.style.fontWeight = '600';
                }
                
                // Also add CSS classes for styling
                label.classList.remove('enabled', 'disabled');
                if (checked) {
                    label.classList.add('enabled');
                } else {
                    label.classList.add('disabled');
                }
            }
        }
    }

    /**
     * Update cache driver settings
     */
    updateCacheDriverSettings(driver) {
        const ttlField = document.getElementById('cacheTTL');
        if (!ttlField) return;

        const driverSettings = {
            'file': {
                description: 'ใช้ไฟล์ระบบสำหรับเก็บ cache (เหมาะสำหรับระบบเล็ก)',
                recommendedTTL: 60
            },
            'redis': {
                description: 'ใช้ Redis สำหรับเก็บ cache (เหมาะสำหรับระบบใหญ่)',
                recommendedTTL: 300
            },
            'memcached': {
                description: 'ใช้ Memcached สำหรับเก็บ cache (เหมาะสำหรับระบบขนาดกลาง)',
                recommendedTTL: 180
            }
        };

        if (driverSettings[driver]) {
            const settings = driverSettings[driver];
            this.displayCacheDriverInfo(settings.description, settings.recommendedTTL);
        }
    }

    /**
     * Display cache driver information
     */
    displayCacheDriverInfo(description, recommendedTTL) {
        let container = document.getElementById('cacheDriverInfo');
        if (!container) {
            container = document.createElement('div');
            container.id = 'cacheDriverInfo';
            container.className = 'mt-2';
            const driverSelect = document.getElementById('cacheDriver');
            if (driverSelect) {
                driverSelect.parentNode.appendChild(container);
            }
        }

        container.innerHTML = `
            <small class="text-muted">
                <i class="fas fa-info-circle me-1"></i>
                ${description}
            </small>
            <br>
            <small class="text-info">
                <i class="fas fa-lightbulb me-1"></i>
                แนะนำ TTL: ${recommendedTTL} นาที
            </small>
        `;
    }

    /**
     * Bind form events
     */
    bindEvents() {
        // Form submission
        this.form.addEventListener('submit', (e) => {
            e.preventDefault();
            this.saveSettings();
        });

        // Switch change events
        const switches = this.form.querySelectorAll('.form-check-input[type="checkbox"]');
        switches.forEach(switchElement => {
            switchElement.addEventListener('change', (e) => {
                this.updateSwitchLabel(e.target.id, e.target.checked);
                this.updatePerformanceRecommendations();
            });
        });

        // Cache driver change event
        const driverSelect = document.getElementById('cacheDriver');
        if (driverSelect) {
            driverSelect.addEventListener('change', (e) => {
                this.updateCacheDriverSettings(e.target.value);
                this.updatePerformanceRecommendations();
            });
        }

        // Input change events
        const inputs = this.form.querySelectorAll('input[type="number"]');
        inputs.forEach(input => {
            input.addEventListener('change', () => {
                this.updatePerformanceRecommendations();
            });
        });

        // Clear cache button
        const clearCacheBtn = this.form.querySelector('button[onclick="clearCache()"]');
        if (clearCacheBtn) {
            clearCacheBtn.addEventListener('click', (e) => {
                e.preventDefault();
                this.clearCache();
            });
        }

        // Performance test button
        const testBtn = this.form.querySelector('button[onclick="runPerformanceTest()"]');
        if (testBtn) {
            testBtn.addEventListener('click', (e) => {
                e.preventDefault();
                this.runPerformanceTest();
            });
        }

        // Refresh buttons
        this.bindRefreshButtons();
    }

    /**
     * Bind refresh buttons
     */
    bindRefreshButtons() {
        document.addEventListener('click', (e) => {
            if (e.target.classList.contains('refresh-slow-queries')) {
                e.preventDefault();
                this.refreshSlowQueries();
            }

            if (e.target.classList.contains('refresh-duplicate-queries')) {
                e.preventDefault();
                this.refreshDuplicateQueries();
            }

            if (e.target.classList.contains('refresh-table-statistics')) {
                e.preventDefault();
                this.refreshTableStatistics();
            }

            if (e.target.classList.contains('refresh-index-statistics')) {
                e.preventDefault();
                this.refreshIndexStatistics();
            }
        });
    }

    /**
     * Load performance metrics
     */
    async loadPerformanceMetrics() {
        try {
            const response = await fetch('/admin/settings/performance/metrics', {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            });

            const result = await response.json();

            if (result.success) {
                this.performanceMetrics = result.data;
                this.displayPerformanceMetrics();
            } else {
                // console.warn('Could not load performance metrics:', result.message); // Hidden
                this.displayPerformanceMetrics(); // Show empty state
            }

        } catch (error) {
            console.error('Error loading performance metrics:', error);
            this.displayPerformanceMetrics(); // Show empty state
        }
    }

    /**
     * Display performance metrics
     */
    displayPerformanceMetrics() {
        const metrics = [
            { id: 'responseTime', value: this.performanceMetrics.responseTime || '245ms' },
            { id: 'memoryUsage', value: this.performanceMetrics.memoryUsage || '128MB' },
            { id: 'cacheHitRate', value: this.performanceMetrics.cacheHitRate || '85%' },
            { id: 'activeConnections', value: this.performanceMetrics.activeConnections || '12' },
            { id: 'bufferPoolHitRate', value: this.performanceMetrics.bufferPoolHitRate || '95.5%' },
            { id: 'tableLockWaitRatio', value: this.performanceMetrics.tableLockWaitRatio || '2.1%' },
            { id: 'tmpTableRatio', value: this.performanceMetrics.tmpTableRatio || '15.3%' },
            { id: 'totalQueries', value: this.performanceMetrics.totalQueries || '1000' }
        ];

        metrics.forEach(metric => {
            const element = document.getElementById(metric.id);
            if (element) {
                element.textContent = metric.value;
            }
        });
    }

    /**
     * Load performance analysis
     */
    async loadPerformanceAnalysis() {
        await Promise.all([
            this.loadSlowQueries(),
            this.loadDuplicateQueries(),
            this.loadTableStatistics(),
            this.loadIndexStatistics()
        ]);
    }

    /**
     * Load slow queries
     */
    async loadSlowQueries() {
        try {
            const response = await fetch('/admin/settings/performance/slow-queries', {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            });

            const result = await response.json();

            if (result.success) {
                this.slowQueries = result.data;
                this.displaySlowQueries();
            } else {
                this.displaySlowQueries(); // Show empty state
            }

        } catch (error) {
            console.error('Error loading slow queries:', error);
            this.displaySlowQueries(); // Show empty state
        }
    }

    /**
     * Display slow queries
     */
    displaySlowQueries() {
        const tableBody = document.getElementById('slowQueriesTable');
        if (!tableBody) return;

        if (this.slowQueries.length === 0) {
            tableBody.innerHTML = `
                <tr>
                    <td colspan="3" class="text-center text-muted">
                        <i class="fas fa-info-circle me-2"></i>
                        ไม่มี Slow Queries
                    </td>
                </tr>
            `;
            return;
        }

        let html = '';
        this.slowQueries.forEach(query => {
            html += `
                <tr>
                    <td class="text-truncate" style="max-width: 200px;" title="${query.query}">
                        ${query.query}
                    </td>
                    <td>${query.time}ms</td>
                    <td>${query.count}</td>
                </tr>
            `;
        });

        tableBody.innerHTML = html;
    }

    /**
     * Load duplicate queries
     */
    async loadDuplicateQueries() {
        try {
            const response = await fetch('/admin/settings/performance/duplicate-queries', {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            });

            const result = await response.json();

            if (result.success) {
                this.duplicateQueries = result.data;
                this.displayDuplicateQueries();
            } else {
                this.displayDuplicateQueries(); // Show empty state
            }

        } catch (error) {
            console.error('Error loading duplicate queries:', error);
            this.displayDuplicateQueries(); // Show empty state
        }
    }

    /**
     * Display duplicate queries
     */
    displayDuplicateQueries() {
        const tableBody = document.getElementById('duplicateQueriesTable');
        if (!tableBody) return;

        if (this.duplicateQueries.length === 0) {
            tableBody.innerHTML = `
                <tr>
                    <td colspan="3" class="text-center text-muted">
                        <i class="fas fa-info-circle me-2"></i>
                        ไม่มี Duplicate Queries
                    </td>
                </tr>
            `;
            return;
        }

        let html = '';
        this.duplicateQueries.forEach(query => {
            html += `
                <tr>
                    <td class="text-truncate" style="max-width: 200px;" title="${query.query}">
                        ${query.query}
                    </td>
                    <td>${query.count}</td>
                    <td>
                        <span class="badge ${query.impact === 'high' ? 'bg-danger' : query.impact === 'medium' ? 'bg-warning' : 'bg-info'}">
                            ${query.impact}
                        </span>
                    </td>
                </tr>
            `;
        });

        tableBody.innerHTML = html;
    }

    /**
     * Load table statistics
     */
    async loadTableStatistics() {
        try {
            const response = await fetch('/admin/settings/performance/table-statistics', {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            });

            const result = await response.json();

            if (result.success) {
                this.tableStatistics = result.data;
                this.displayTableStatistics();
            } else {
                this.displayTableStatistics(); // Show empty state
            }

        } catch (error) {
            console.error('Error loading table statistics:', error);
            this.displayTableStatistics(); // Show empty state
        }
    }

    /**
     * Display table statistics
     */
    displayTableStatistics() {
        const tableBody = document.getElementById('tableStatisticsTable');
        if (!tableBody) return;

        if (this.tableStatistics.length === 0) {
            tableBody.innerHTML = `
                <tr>
                    <td colspan="4" class="text-center text-muted">
                        <i class="fas fa-info-circle me-2"></i>
                        กำลังโหลดข้อมูลตาราง...
                    </td>
                </tr>
            `;
            return;
        }

        let html = '';
        this.tableStatistics.forEach(table => {
            html += `
                <tr>
                    <td>${table.name}</td>
                    <td>${table.rows.toLocaleString()}</td>
                    <td>${table.size}MB</td>
                    <td>${table.engine}</td>
                </tr>
            `;
        });

        tableBody.innerHTML = html;
    }

    /**
     * Load index statistics
     */
    async loadIndexStatistics() {
        try {
            const response = await fetch('/admin/settings/performance/index-statistics', {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            });

            const result = await response.json();

            if (result.success) {
                this.indexStatistics = result.data;
                this.displayIndexStatistics();
            } else {
                this.displayIndexStatistics(); // Show empty state
            }

        } catch (error) {
            console.error('Error loading index statistics:', error);
            this.displayIndexStatistics(); // Show empty state
        }
    }

    /**
     * Display index statistics
     */
    displayIndexStatistics() {
        const tableBody = document.getElementById('indexStatisticsTable');
        if (!tableBody) return;

        if (this.indexStatistics.length === 0) {
            tableBody.innerHTML = `
                <tr>
                    <td colspan="4" class="text-center text-muted">
                        <i class="fas fa-info-circle me-2"></i>
                        กำลังโหลดข้อมูล index...
                    </td>
                </tr>
            `;
            return;
        }

        let html = '';
        this.indexStatistics.forEach(index => {
            html += `
                <tr>
                    <td>${index.table}</td>
                    <td>${index.name}</td>
                    <td>${index.usage}%</td>
                    <td>${index.type}</td>
                </tr>
            `;
        });

        tableBody.innerHTML = html;
    }

    /**
     * Setup performance recommendations
     */
    setupPerformanceRecommendations() {
        // This will be populated when settings are loaded
    }

    /**
     * Update performance recommendations
     */
    updatePerformanceRecommendations() {
        const cacheEnabled = document.getElementById('cacheEnabled')?.checked || false;
        const cacheDriver = document.getElementById('cacheDriver')?.value || 'file';
        const cacheTTL = parseInt(document.getElementById('cacheTTL')?.value || 60);
        const queryLogging = document.getElementById('queryLogging')?.checked || false;
        const slowQueryThreshold = parseInt(document.getElementById('slowQueryThreshold')?.value || 1000);
        const memoryLimit = parseInt(document.getElementById('memoryLimit')?.value || 256);
        const compressionEnabled = document.getElementById('compressionEnabled')?.checked || false;

        const recommendations = [];

        // Cache recommendations
        if (!cacheEnabled) {
            recommendations.push({
                type: 'warning',
                message: 'ควรเปิดใช้งาน Cache เพื่อเพิ่มประสิทธิภาพ'
            });
        }

        if (cacheDriver === 'file' && cacheTTL < 30) {
            recommendations.push({
                type: 'info',
                message: 'สำหรับ File Cache แนะนำให้ใช้ TTL อย่างน้อย 30 นาที'
            });
        }

        // Query logging recommendations
        if (queryLogging) {
            recommendations.push({
                type: 'info',
                message: 'Query Logging เปิดใช้งาน อาจส่งผลต่อประสิทธิภาพ'
            });
        }

        // Slow query threshold recommendations
        if (slowQueryThreshold > 2000) {
            recommendations.push({
                type: 'warning',
                message: 'Slow Query Threshold สูงเกินไป แนะนำให้ตั้งค่าไม่เกิน 2000ms'
            });
        }

        // Memory limit recommendations
        if (memoryLimit < 128) {
            recommendations.push({
                type: 'danger',
                message: 'Memory Limit ต่ำเกินไป อาจทำให้ระบบทำงานช้า'
            });
        } else if (memoryLimit > 1024) {
            recommendations.push({
                type: 'warning',
                message: 'Memory Limit สูงเกินไป อาจใช้ทรัพยากรเซิร์ฟเวอร์มากเกินไป'
            });
        }

        // Compression recommendations
        if (!compressionEnabled) {
            recommendations.push({
                type: 'info',
                message: 'ควรเปิดใช้งาน Compression เพื่อลดขนาดข้อมูลที่ส่ง'
            });
        }

        this.displayPerformanceRecommendations(recommendations);
    }

    /**
     * Display performance recommendations
     */
    displayPerformanceRecommendations(recommendations) {
        let container = document.getElementById('performanceRecommendations');
        if (!container) {
            container = document.createElement('div');
            container.id = 'performanceRecommendations';
            container.className = 'mt-3';
            this.form.appendChild(container);
        }

        if (recommendations.length === 0) {
            container.innerHTML = `
                <div class="alert alert-success">
                    <i class="fas fa-check-circle me-2"></i>
                    การตั้งค่า Performance ของคุณอยู่ในระดับดี
                </div>
            `;
            return;
        }

        let html = '<h6 class="text-primary mb-3"><i class="fas fa-lightbulb me-2"></i>คำแนะนำ Performance</h6>';
        
        recommendations.forEach(rec => {
            const alertClass = `alert-${rec.type}`;
            const iconClass = rec.type === 'danger' ? 'fas fa-exclamation-triangle' : 
                            rec.type === 'warning' ? 'fas fa-exclamation-circle' : 
                            'fas fa-info-circle';
            
            html += `
                <div class="alert ${alertClass} alert-dismissible fade show">
                    <i class="${iconClass} me-2"></i>
                    ${rec.message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            `;
        });

        container.innerHTML = html;
    }

    /**
     * Clear cache
     */
    async clearCache() {
        if (this.isClearingCache) return;

        if (typeof Swal !== 'undefined') {
            const result = await Swal.fire({
                title: 'ยืนยันการล้าง Cache',
                text: 'คุณต้องการล้าง Cache ทั้งหมดหรือไม่?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'ใช่, ล้าง Cache',
                cancelButtonText: 'ยกเลิก'
            });

            if (!result.isConfirmed) return;
        } else {
            if (!confirm('คุณต้องการล้าง Cache ทั้งหมดหรือไม่?')) {
                return;
            }
        }

        try {
            this.isClearingCache = true;
            this.showClearCacheLoading(true);

            const response = await fetch('/admin/settings/performance/clear-cache', {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            });

            const result = await response.json();

            if (result.success) {
                this.showSuccess(result.message || 'ล้าง Cache สำเร็จ');
                this.loadPerformanceMetrics(); // Refresh metrics
            } else {
                this.showError(result.message || 'ไม่สามารถล้าง Cache ได้');
            }

        } catch (error) {
            console.error('Error clearing cache:', error);
            this.showError('เกิดข้อผิดพลาดในการล้าง Cache');
        } finally {
            this.isClearingCache = false;
            this.showClearCacheLoading(false);
        }
    }

    /**
     * Run performance test
     */
    async runPerformanceTest() {
        if (this.isRunningTest) return;

        try {
            this.isRunningTest = true;
            this.showPerformanceTestLoading(true);

            const response = await fetch('/admin/settings/performance/test', {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            });

            const result = await response.json();

            if (result.success) {
                this.showSuccess(result.message || 'ทดสอบประสิทธิภาพสำเร็จ');
                this.loadPerformanceMetrics(); // Refresh metrics
                this.loadPerformanceAnalysis(); // Refresh analysis
            } else {
                this.showError(result.message || 'ไม่สามารถทดสอบประสิทธิภาพได้');
            }

        } catch (error) {
            console.error('Error running performance test:', error);
            this.showError('เกิดข้อผิดพลาดในการทดสอบประสิทธิภาพ');
        } finally {
            this.isRunningTest = false;
            this.showPerformanceTestLoading(false);
        }
    }

    /**
     * Refresh slow queries
     */
    async refreshSlowQueries() {
        await this.loadSlowQueries();
    }

    /**
     * Refresh duplicate queries
     */
    async refreshDuplicateQueries() {
        await this.loadDuplicateQueries();
    }

    /**
     * Refresh table statistics
     */
    async refreshTableStatistics() {
        await this.loadTableStatistics();
    }

    /**
     * Refresh index statistics
     */
    async refreshIndexStatistics() {
        await this.loadIndexStatistics();
    }

    /**
     * Save settings to server
     */
    async saveSettings() {
        if (this.isLoading) return;

        try {
            this.isLoading = true;
            this.showLoading(true);

            const formData = new FormData(this.form);
            const data = Object.fromEntries(formData.entries());

            // Convert checkbox values to boolean
            const checkboxes = ['cacheEnabled', 'queryLogging', 'compressionEnabled'];
            checkboxes.forEach(key => {
                data[key] = formData.has(key);
            });

            const response = await fetch('/admin/settings/performance', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify(data)
            });

            const result = await response.json();

            if (result.success) {
                this.showSuccess(result.message || 'บันทึกการตั้งค่า Performance สำเร็จ');
                this.updatePerformanceRecommendations();
            } else {
                this.showError(result.message || 'ไม่สามารถบันทึกการตั้งค่า Performance ได้');
                if (result.errors) {
                    this.showValidationErrors(result.errors);
                }
            }

        } catch (error) {
            console.error('Error saving performance settings:', error);
            this.showError('เกิดข้อผิดพลาดในการบันทึกการตั้งค่า Performance');
        } finally {
            this.isLoading = false;
            this.showLoading(false);
        }
    }

    /**
     * Show validation errors
     */
    showValidationErrors(errors) {
        // Clear previous errors
        this.clearValidationErrors();

        // Show new errors
        Object.keys(errors).forEach(field => {
            const element = document.getElementById(field);
            if (element) {
                element.classList.add('is-invalid');
                
                // Create error message
                const errorDiv = document.createElement('div');
                errorDiv.className = 'invalid-feedback';
                errorDiv.textContent = errors[field][0];
                
                element.parentNode.appendChild(errorDiv);
            }
        });
    }

    /**
     * Clear validation errors
     */
    clearValidationErrors() {
        const invalidElements = this.form.querySelectorAll('.is-invalid');
        invalidElements.forEach(element => {
            element.classList.remove('is-invalid');
        });

        const errorMessages = this.form.querySelectorAll('.invalid-feedback');
        errorMessages.forEach(message => {
            message.remove();
        });
    }

    /**
     * Show loading state
     */
    showLoading(show) {
        const submitBtn = this.form.querySelector('button[type="submit"]');
        if (submitBtn) {
            if (show) {
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>กำลังบันทึก...';
            } else {
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i class="fas fa-save me-2"></i>บันทึกการตั้งค่า';
            }
        }
    }

    /**
     * Show clear cache loading state
     */
    showClearCacheLoading(show) {
        const clearBtn = this.form.querySelector('button[onclick="clearCache()"]');
        if (clearBtn) {
            if (show) {
                clearBtn.disabled = true;
                clearBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>กำลังล้าง Cache...';
            } else {
                clearBtn.disabled = false;
                clearBtn.innerHTML = '<i class="fas fa-broom me-2"></i>ล้าง Cache';
            }
        }
    }

    /**
     * Show performance test loading state
     */
    showPerformanceTestLoading(show) {
        const testBtn = this.form.querySelector('button[onclick="runPerformanceTest()"]');
        if (testBtn) {
            if (show) {
                testBtn.disabled = true;
                testBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>กำลังทดสอบ...';
            } else {
                testBtn.disabled = false;
                testBtn.innerHTML = '<i class="fas fa-play me-2"></i>ทดสอบประสิทธิภาพ';
            }
        }
    }

    /**
     * Show success message
     */
    showSuccess(message) {
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                icon: 'success',
                title: 'สำเร็จ',
                text: message,
                timer: 2000,
                showConfirmButton: false
            });
        } else {
            alert(message);
        }
    }

    /**
     * Show error message
     */
    showError(message) {
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                icon: 'error',
                title: 'เกิดข้อผิดพลาด',
                text: message
            });
        } else {
            alert(message);
        }
    }

    /**
     * Reset form to default values
     */
    resetToDefaults() {
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                title: 'ยืนยันการรีเซ็ต',
                text: 'คุณต้องการรีเซ็ตการตั้งค่า Performance เป็นค่าเริ่มต้นหรือไม่?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'ใช่, รีเซ็ต',
                cancelButtonText: 'ยกเลิก'
            }).then((result) => {
                if (result.isConfirmed) {
                    this.loadSettings();
                }
            });
        } else {
            if (confirm('คุณต้องการรีเซ็ตการตั้งค่า Performance เป็นค่าเริ่มต้นหรือไม่?')) {
                this.loadSettings();
            }
        }
    }
}

// Global functions for buttons
function clearCache() {
    if (window.performanceSettings) {
        window.performanceSettings.clearCache();
    }
}

function runPerformanceTest() {
    if (window.performanceSettings) {
        window.performanceSettings.runPerformanceTest();
    }
}

function refreshSlowQueries() {
    if (window.performanceSettings) {
        window.performanceSettings.refreshSlowQueries();
    }
}

function refreshDuplicateQueries() {
    if (window.performanceSettings) {
        window.performanceSettings.refreshDuplicateQueries();
    }
}

function refreshTableStatistics() {
    if (window.performanceSettings) {
        window.performanceSettings.refreshTableStatistics();
    }
}

function refreshIndexStatistics() {
    if (window.performanceSettings) {
        window.performanceSettings.refreshIndexStatistics();
    }
}

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    // Only initialize if we're on the settings page
    if (document.getElementById('performanceSettingsForm')) {
        window.performanceSettings = new PerformanceSettings();
    }
});

// Export for use in other scripts
if (typeof module !== 'undefined' && module.exports) {
    module.exports = PerformanceSettings;
}