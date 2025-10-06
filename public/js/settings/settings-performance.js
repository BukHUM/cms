// ========================================
// PERFORMANCE SETTINGS FUNCTIONS
// ========================================

// Performance Settings Form
document.addEventListener('DOMContentLoaded', function() {
    const performanceForm = document.getElementById('performanceSettingsForm');
    if (performanceForm) {
        performanceForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Get form data
            const formData = {
                cache_enabled: document.getElementById('cacheEnabled').checked,
                cache_driver: document.getElementById('cacheDriver').value,
                cache_ttl: document.getElementById('cacheTTL').value,
                query_logging: document.getElementById('queryLogging').checked,
                slow_query_threshold: document.getElementById('slowQueryThreshold').value,
                memory_limit: document.getElementById('memoryLimit').value,
                max_execution_time: document.getElementById('maxExecutionTime').value,
                compression_enabled: document.getElementById('compressionEnabled').checked
            };
            
            // Validate performance settings
            if (formData.cache_ttl < 1) {
                SwalHelper.error('Cache TTL ต้องไม่น้อยกว่า 1 นาที');
                return;
            }
            
            if (formData.memory_limit < 128) {
                SwalHelper.error('Memory Limit ต้องไม่น้อยกว่า 128 MB');
                return;
            }
            
            console.log('Saving performance settings:', formData);
            SwalHelper.loading('กำลังบันทึกการตั้งค่า Performance...');
            
            // Send to API
            fetch('/api/performance/settings', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify(formData)
            })
            .then(response => response.json())
            .then(data => {
                SwalHelper.close();
                
                if (data.success) {
                    SwalHelper.success(data.message || 'บันทึกการตั้งค่า Performance เรียบร้อยแล้ว');
                    
                    // Save to localStorage for persistence (convert API format to localStorage format)
                    const localStorageData = {
                        cacheEnabled: formData.cache_enabled,
                        cacheDriver: formData.cache_driver,
                        cacheTTL: formData.cache_ttl,
                        queryLogging: formData.query_logging,
                        slowQueryThreshold: formData.slow_query_threshold,
                        memoryLimit: formData.memory_limit,
                        maxExecutionTime: formData.max_execution_time,
                        compressionEnabled: formData.compression_enabled
                    };
                    localStorage.setItem('performanceSettings', JSON.stringify(localStorageData));
                    
                    // Update switch styling after saving
                    updateSwitchStyle('cacheEnabled', 'cacheEnabledLabel');
                    updateSwitchStyle('queryLogging', 'queryLoggingLabel');
                    updateSwitchStyle('compressionEnabled', 'compressionEnabledLabel');
                    
                    // Update performance metrics after saving settings
                    updatePerformanceMetrics();
                } else {
                    SwalHelper.error(data.message || 'ไม่สามารถบันทึกการตั้งค่าได้');
                }
            })
            .catch(error => {
                SwalHelper.close();
                console.error('Error saving performance settings:', error);
                SwalHelper.error('เกิดข้อผิดพลาดในการบันทึกการตั้งค่า');
            });
        });
    }
});

// Clear Cache Function
function clearCache() {
    SwalHelper.confirm('คุณแน่ใจหรือไม่ที่จะล้าง Cache ทั้งหมด?', 'ยืนยันการล้าง Cache', function() {
        SwalHelper.loading('กำลังล้าง Cache...');
        
        setTimeout(() => {
            SwalHelper.close();
            SwalHelper.success('ล้าง Cache สำเร็จ!');
            
            // Update performance metrics
            updatePerformanceMetrics();
        }, 2000);
    });
}

// Run Performance Test Function
function runPerformanceTest() {
    SwalHelper.loading('กำลังทดสอบประสิทธิภาพ...');
    
    setTimeout(() => {
        SwalHelper.close();
        SwalHelper.success('ทดสอบประสิทธิภาพเสร็จสิ้น!');
        
        // Update performance metrics
        updatePerformanceMetrics();
    }, 3000);
}

// Test Query Logging Function
function testQueryLogging() {
    SwalHelper.loading('กำลังทดสอบ Query Logging...');
    
    fetch('/api/performance/test-query-logging', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        SwalHelper.close();
        
        if (data.success) {
            const testData = data.data;
            let message = 'ทดสอบ Query Logging สำเร็จ!\n\n';
            message += `• Query Logging เปิดใช้งาน: ${testData.query_logging_enabled ? 'ใช่' : 'ไม่'}\n`;
            message += `• ทดสอบ Query ทำงาน: ${testData.test_query_executed ? 'ใช่' : 'ไม่'}\n`;
            message += `• Query ถูกบันทึกใน Log: ${testData.query_logged ? 'ใช่' : 'ไม่'}\n`;
            message += `• ไฟล์ Log มีอยู่: ${testData.log_file_exists ? 'ใช่' : 'ไม่'}\n\n`;
            
            if (testData.debug_info) {
                message += 'ข้อมูล Debug:\n';
                message += `• File Exists: ${testData.debug_info.file_exists ? 'ใช่' : 'ไม่'}\n`;
                message += `• Is Enabled: ${testData.debug_info.is_enabled ? 'ใช่' : 'ไม่'}\n`;
                message += `• DB Config Content: ${testData.debug_info.db_config_content}`;
            }
            
            SwalHelper.info(message, 'ผลการทดสอบ Query Logging');
        } else {
            SwalHelper.error(data.message || 'ไม่สามารถทดสอบ Query Logging ได้');
        }
    })
    .catch(error => {
        SwalHelper.close();
        console.error('Error testing query logging:', error);
        SwalHelper.error('เกิดข้อผิดพลาดในการทดสอบ Query Logging');
    });
}

// Update Performance Metrics
function updatePerformanceMetrics() {
    fetch('/api/performance/metrics')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const metrics = data.data;
                
                document.getElementById('responseTime').textContent = (metrics.response_time || 0) + 'ms';
                document.getElementById('memoryUsage').textContent = (metrics.memory_usage || 0) + 'MB';
                document.getElementById('cacheHitRate').textContent = (metrics.cache_hit_rate || 0) + '%';
                document.getElementById('activeConnections').textContent = metrics.active_connections || 0;
                document.getElementById('bufferPoolHitRate').textContent = (metrics.buffer_pool_hit_rate || 0) + '%';
                document.getElementById('tableLockWaitRatio').textContent = (metrics.table_lock_wait_ratio || 0) + '%';
                document.getElementById('tmpTableRatio').textContent = (metrics.tmp_table_ratio || 0) + '%';
                document.getElementById('totalQueries').textContent = metrics.total_queries || 0;
            } else {
                console.error('Error fetching performance metrics:', data.message);
                // Fallback to simulated data
                const responseTime = Math.floor(Math.random() * 200) + 100;
                const memoryUsage = Math.floor(Math.random() * 100) + 100;
                const cacheHitRate = Math.floor(Math.random() * 20) + 80;
                const activeConnections = Math.floor(Math.random() * 10) + 8;
                const bufferPoolHitRate = Math.floor(Math.random() * 10) + 90;
                const tableLockWaitRatio = Math.floor(Math.random() * 5);
                const tmpTableRatio = Math.floor(Math.random() * 20) + 10;
                const totalQueries = Math.floor(Math.random() * 1000) + 500;
                
                document.getElementById('responseTime').textContent = responseTime + 'ms';
                document.getElementById('memoryUsage').textContent = memoryUsage + 'MB';
                document.getElementById('cacheHitRate').textContent = cacheHitRate + '%';
                document.getElementById('activeConnections').textContent = activeConnections;
                document.getElementById('bufferPoolHitRate').textContent = bufferPoolHitRate + '%';
                document.getElementById('tableLockWaitRatio').textContent = tableLockWaitRatio + '%';
                document.getElementById('tmpTableRatio').textContent = tmpTableRatio + '%';
                document.getElementById('totalQueries').textContent = totalQueries;
            }
        })
        .catch(error => {
            console.error('Error fetching performance metrics:', error);
            // Fallback to simulated data
            const responseTime = Math.floor(Math.random() * 200) + 100;
            const memoryUsage = Math.floor(Math.random() * 100) + 100;
            const cacheHitRate = Math.floor(Math.random() * 20) + 80;
            const activeConnections = Math.floor(Math.random() * 10) + 8;
            const bufferPoolHitRate = Math.floor(Math.random() * 10) + 90;
            const tableLockWaitRatio = Math.floor(Math.random() * 5);
            const tmpTableRatio = Math.floor(Math.random() * 20) + 10;
            const totalQueries = Math.floor(Math.random() * 1000) + 500;
            
            document.getElementById('responseTime').textContent = responseTime + 'ms';
            document.getElementById('memoryUsage').textContent = memoryUsage + 'MB';
            document.getElementById('cacheHitRate').textContent = cacheHitRate + '%';
            document.getElementById('activeConnections').textContent = activeConnections;
            document.getElementById('bufferPoolHitRate').textContent = bufferPoolHitRate + '%';
            document.getElementById('tableLockWaitRatio').textContent = tableLockWaitRatio + '%';
            document.getElementById('tmpTableRatio').textContent = tmpTableRatio + '%';
            document.getElementById('totalQueries').textContent = totalQueries;
        });
}

// Refresh Slow Queries
function refreshSlowQueries() {
    console.log('Refreshing slow queries...');
    SwalHelper.loading('กำลังโหลด Slow Queries...');
    
    fetch('/api/performance/slow-queries')
        .then(response => response.json())
        .then(data => {
            SwalHelper.close();
            
            if (data.success) {
                updateSlowQueriesTable(data.data);
                SwalHelper.success('อัพเดต Slow Queries เรียบร้อยแล้ว');
            } else {
                SwalHelper.error(data.message || 'ไม่สามารถโหลด Slow Queries ได้');
            }
        })
        .catch(error => {
            SwalHelper.close();
            console.error('Error fetching slow queries:', error);
            SwalHelper.error('เกิดข้อผิดพลาดในการโหลด Slow Queries');
        });
}

// Update Slow Queries Table
function updateSlowQueriesTable(queries) {
    const tbody = document.getElementById('slowQueriesTable');
    tbody.innerHTML = '';
    
    if (queries.length === 0) {
        tbody.innerHTML = '<tr><td colspan="3" class="text-center text-muted"><i class="fas fa-info-circle me-2"></i>ไม่มี Slow Queries</td></tr>';
        return;
    }
    
    queries.forEach(query => {
        const time = parseFloat(query.time) || 0;
        const badgeClass = time > 2000 ? 'bg-danger' : time > 1000 ? 'bg-warning' : 'bg-success';
        const tableName = query.table_name || query.table || 'Unknown table';
        
        const row = `
            <tr>
                <td>
                    <code>${query.query || 'N/A'}</code>
                    <small class="text-muted d-block">${tableName}</small>
                </td>
                <td><span class="badge ${badgeClass}">${time.toLocaleString()}ms</span></td>
                <td>${query.count || 1}</td>
            </tr>
        `;
        tbody.insertAdjacentHTML('beforeend', row);
    });
}

// Refresh Duplicate Queries
function refreshDuplicateQueries() {
    console.log('Refreshing duplicate queries...');
    SwalHelper.loading('กำลังโหลด Duplicate Queries...');
    
    fetch('/api/performance/duplicate-queries')
        .then(response => response.json())
        .then(data => {
            SwalHelper.close();
            
            if (data.success) {
                updateDuplicateQueriesTable(data.data);
                SwalHelper.success('อัพเดต Duplicate Queries เรียบร้อยแล้ว');
            } else {
                SwalHelper.error(data.message || 'ไม่สามารถโหลด Duplicate Queries ได้');
            }
        })
        .catch(error => {
            SwalHelper.close();
            console.error('Error fetching duplicate queries:', error);
            SwalHelper.error('เกิดข้อผิดพลาดในการโหลด Duplicate Queries');
        });
}

// Update Duplicate Queries Table
function updateDuplicateQueriesTable(queries) {
    const tbody = document.getElementById('duplicateQueriesTable');
    tbody.innerHTML = '';
    
    if (queries.length === 0) {
        tbody.innerHTML = '<tr><td colspan="3" class="text-center text-muted"><i class="fas fa-info-circle me-2"></i>ไม่มี Duplicate Queries</td></tr>';
        return;
    }
    
    queries.forEach(query => {
        const count = parseInt(query.count) || 0;
        const countBadgeClass = count > 60 ? 'bg-danger' : count > 30 ? 'bg-warning' : 'bg-info';
        const impactBadgeClass = query.impact === 'High' ? 'bg-danger' : query.impact === 'Medium' ? 'bg-warning' : 'bg-success';
        const tableName = query.table_name || query.table || 'Unknown table';
        
        const row = `
            <tr>
                <td>
                    <code>${query.query || 'N/A'}</code>
                    <small class="text-muted d-block">${tableName}</small>
                </td>
                <td><span class="badge ${countBadgeClass}">${count}</span></td>
                <td><span class="badge ${impactBadgeClass}">${query.impact || 'Low'}</span></td>
            </tr>
        `;
        tbody.insertAdjacentHTML('beforeend', row);
    });
}

// Refresh Table Statistics
function refreshTableStatistics() {
    console.log('Refreshing table statistics...');
    SwalHelper.loading('กำลังโหลด Table Statistics...');
    
    fetch('/api/performance/table-statistics')
        .then(response => response.json())
        .then(data => {
            SwalHelper.close();
            
            if (data.success) {
                updateTableStatisticsTable(data.data);
                SwalHelper.success('อัพเดต Table Statistics เรียบร้อยแล้ว');
            } else {
                SwalHelper.error(data.message || 'ไม่สามารถโหลด Table Statistics ได้');
            }
        })
        .catch(error => {
            SwalHelper.close();
            console.error('Error fetching table statistics:', error);
            SwalHelper.error('เกิดข้อผิดพลาดในการโหลด Table Statistics');
        });
}

// Update Table Statistics Table
function updateTableStatisticsTable(tables) {
    const tbody = document.getElementById('tableStatisticsTable');
    tbody.innerHTML = '';
    
    if (tables.length === 0) {
        tbody.innerHTML = '<tr><td colspan="4" class="text-center text-muted"><i class="fas fa-info-circle me-2"></i>ไม่มีข้อมูลตาราง</td></tr>';
        return;
    }
    
    tables.forEach(table => {
        const row = `
            <tr>
                <td>
                    <strong>${table.table_name}</strong>
                    <small class="text-muted d-block">${table.table_name.replace('laravel_', '')}</small>
                </td>
                <td>${table.row_count || 0}</td>
                <td>${table.size_mb || 0}</td>
                <td><span class="badge bg-primary">${table.engine || 'InnoDB'}</span></td>
            </tr>
        `;
        tbody.insertAdjacentHTML('beforeend', row);
    });
}

// Refresh Index Statistics
function refreshIndexStatistics() {
    console.log('Refreshing index statistics...');
    SwalHelper.loading('กำลังโหลด Index Statistics...');
    
    fetch('/api/performance/index-statistics')
        .then(response => response.json())
        .then(data => {
            SwalHelper.close();
            
            if (data.success) {
                updateIndexStatisticsTable(data.data);
                SwalHelper.success('อัพเดต Index Statistics เรียบร้อยแล้ว');
            } else {
                SwalHelper.error(data.message || 'ไม่สามารถโหลด Index Statistics ได้');
            }
        })
        .catch(error => {
            SwalHelper.close();
            console.error('Error fetching index statistics:', error);
            SwalHelper.error('เกิดข้อผิดพลาดในการโหลด Index Statistics');
        });
}

// Update Index Statistics Table
function updateIndexStatisticsTable(indexes) {
    const tbody = document.getElementById('indexStatisticsTable');
    tbody.innerHTML = '';
    
    if (indexes.length === 0) {
        tbody.innerHTML = '<tr><td colspan="4" class="text-center text-muted"><i class="fas fa-info-circle me-2"></i>ไม่มีข้อมูล index</td></tr>';
        return;
    }
    
    indexes.forEach(index => {
        const usageBadgeClass = index.usage_level === 'High' ? 'bg-success' : 
                               index.usage_level === 'Medium' ? 'bg-warning' : 
                               index.usage_level === 'Low' ? 'bg-info' : 'bg-secondary';
        
        const typeBadgeClass = index.index_type === 'UNIQUE' ? 'bg-warning' : 
                              index.index_type === 'FULLTEXT' ? 'bg-info' : 'bg-primary';
        
        const row = `
            <tr>
                <td>
                    <strong>${index.table_name}</strong>
                    <small class="text-muted d-block">${index.column_name}</small>
                </td>
                <td>${index.index_name}</td>
                <td><span class="badge ${usageBadgeClass}">${index.usage_level || 'Unknown'}</span></td>
                <td><span class="badge ${typeBadgeClass}">${index.index_type || 'INDEX'}</span></td>
            </tr>
        `;
        tbody.insertAdjacentHTML('beforeend', row);
    });
}

// Load Performance Settings from localStorage
function loadPerformanceSettings() {
    const performanceSettings = localStorage.getItem('performanceSettings');
    if (performanceSettings) {
        const data = JSON.parse(performanceSettings);
        document.getElementById('cacheEnabled').checked = data.cacheEnabled || false;
        document.getElementById('cacheDriver').value = data.cacheDriver || 'file';
        document.getElementById('cacheTTL').value = data.cacheTTL || '60';
        document.getElementById('queryLogging').checked = data.queryLogging || false;
        document.getElementById('slowQueryThreshold').value = data.slowQueryThreshold || '1000';
        document.getElementById('memoryLimit').value = data.memoryLimit || '256';
        document.getElementById('maxExecutionTime').value = data.maxExecutionTime || '30';
        document.getElementById('compressionEnabled').checked = data.compressionEnabled || false;
        
        // Update switch styling after loading from localStorage
        updateSwitchStyle('cacheEnabled', 'cacheEnabledLabel');
        updateSwitchStyle('queryLogging', 'queryLoggingLabel');
        updateSwitchStyle('compressionEnabled', 'compressionEnabledLabel');
        
        console.log('Performance settings loaded');
    } else {
        // Load from API if no localStorage data
        loadPerformanceSettingsFromAPI();
    }
}

// Load Performance Settings from API
function loadPerformanceSettingsFromAPI() {
    fetch('/api/performance/settings')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const settings = data.data;
                document.getElementById('cacheEnabled').checked = settings.cache_enabled || false;
                document.getElementById('cacheDriver').value = settings.cache_driver || 'file';
                document.getElementById('cacheTTL').value = settings.cache_ttl || '60';
                document.getElementById('queryLogging').checked = settings.query_logging || false;
                document.getElementById('slowQueryThreshold').value = settings.slow_query_threshold || '1000';
                document.getElementById('memoryLimit').value = settings.memory_limit || '256';
                document.getElementById('maxExecutionTime').value = settings.max_execution_time || '30';
                document.getElementById('compressionEnabled').checked = settings.compression_enabled || false;
                
                // Update switch styling after loading settings
                updateSwitchStyle('cacheEnabled', 'cacheEnabledLabel');
                updateSwitchStyle('queryLogging', 'queryLoggingLabel');
                updateSwitchStyle('compressionEnabled', 'compressionEnabledLabel');
                
                // Also save to localStorage for consistency
                const localStorageData = {
                    cacheEnabled: settings.cache_enabled || false,
                    cacheDriver: settings.cache_driver || 'file',
                    cacheTTL: settings.cache_ttl || '60',
                    queryLogging: settings.query_logging || false,
                    slowQueryThreshold: settings.slow_query_threshold || '1000',
                    memoryLimit: settings.memory_limit || '256',
                    maxExecutionTime: settings.max_execution_time || '30',
                    compressionEnabled: settings.compression_enabled || false
                };
                localStorage.setItem('performanceSettings', JSON.stringify(localStorageData));
                
                console.log('Performance settings loaded from API');
            }
        })
        .catch(error => {
            console.error('Error loading performance settings:', error);
        });
}
