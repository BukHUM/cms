/**
 * Settings General JavaScript - Optimized and Modular
 */

class SettingsGeneralManager {
    constructor() {
        this.searchTimeout = null;
        this.currentSearchTerm = '';
        this.currentSettings = [];
        this.init();
    }

    init() {
        this.bindEvents();
        this.initializeComponents();
    }

    bindEvents() {
        // Search functionality
        const searchInput = document.getElementById('search');
        if (searchInput) {
            searchInput.addEventListener('input', (e) => this.handleSearchInput(e));
            searchInput.addEventListener('focus', (e) => this.handleSearchFocus(e));
        }

        // Status filter
        const statusFilter = document.getElementById('status-filter');
        if (statusFilter) {
            statusFilter.addEventListener('change', (e) => this.handleStatusFilter(e));
        }

        // Clear filters
        const clearFilters = document.getElementById('clear-filters');
        if (clearFilters) {
            clearFilters.addEventListener('click', () => this.clearFilters());
        }

        // File upload events
        this.bindFileUploadEvents();

        // Modal events
        this.bindModalEvents();

        // Form submission
        const editForm = document.getElementById('editForm');
        if (editForm) {
            editForm.addEventListener('submit', (e) => this.handleFormSubmit(e));
        }
    }

    initializeComponents() {
        // Initialize any components that need setup
        this.resetFileUploadSections();
    }

    // Search Methods
    handleSearchInput(e) {
        const searchTerm = e.target.value.trim();
        this.currentSearchTerm = searchTerm;
        
        clearTimeout(this.searchTimeout);
        
        if (searchTerm === '') {
            this.hideSuggestions();
            this.resetTableToAll();
            return;
        }
        
        this.showSearchLoading();
        
        this.searchTimeout = setTimeout(() => {
            this.performLiveSearch(searchTerm);
        }, 300);
    }

    handleSearchFocus(e) {
        if (e.target.value.trim() !== '') {
            this.performLiveSearch(e.target.value.trim());
        }
    }

    handleStatusFilter(e) {
        const searchTerm = document.getElementById('search').value.trim();
        const status = e.target.value;
        
        clearTimeout(this.searchTimeout);
        
        if (searchTerm === '' && status === '') {
            this.resetTableToAll();
            return;
        }
        
        this.showSearchLoading();
        
        this.searchTimeout = setTimeout(() => {
            if (searchTerm !== '') {
                this.performLiveSearch(searchTerm, false);
            } else {
                this.performStatusFilter(status);
            }
        }, 300);
    }

    async performLiveSearch(searchTerm, showSuggestions = true) {
        if (searchTerm === '') {
            this.hideSuggestions();
            const status = document.getElementById('status-filter').value;
            if (status && status !== '') {
                this.performStatusFilter(status);
            } else {
                this.resetTableToAll();
            }
            return;
        }
        
        const status = document.getElementById('status-filter').value;
        let url = `${this.getBaseUrl()}?search=${encodeURIComponent(searchTerm)}&ajax=1`;
        if (status) {
            url += `&status=${encodeURIComponent(status)}`;
        }
        
        try {
            const response = await this.fetchData(url);
            this.hideSearchLoading();
            
            if (response.success) {
                this.updateTableWithResults(response.settings);
                if (showSuggestions) {
                    this.showSuggestions(response.suggestions, searchTerm);
                }
            } else {
                console.error('Search failed:', response);
            }
        } catch (error) {
            console.error('Search error:', error);
            this.hideSearchLoading();
        }
    }

    async performStatusFilter(status) {
        if (!status || status === '') {
            this.resetTableToAll();
            return;
        }
        
        const url = `${this.getBaseUrl()}?ajax=1&status=${encodeURIComponent(status)}`;
        
        try {
            const response = await this.fetchData(url);
            this.hideSearchLoading();
            
            if (response.success) {
                this.updateTableWithResults(response.settings);
            } else {
                console.error('Status filter failed:', response);
            }
        } catch (error) {
            console.error('Status filter error:', error);
            this.hideSearchLoading();
        }
    }

    // Table Management
    updateTableWithResults(settings) {
        const tbody = document.querySelector('tbody');
        const mobileCards = document.querySelector('.md\\:hidden .space-y-4');
        const tableHeader = document.querySelector('.text-lg.font-medium.text-gray-900');
        
        this.currentSettings = settings;
        
        if (!tbody) {
            console.error('tbody not found, cannot update table');
            return;
        }
        
        if (tableHeader) {
            tableHeader.textContent = `รายการการตั้งค่า (${settings.length} รายการ)`;
        }
        
        // Update desktop table
        tbody.innerHTML = '';
        settings.forEach(setting => {
            const row = this.createTableRow(setting);
            tbody.appendChild(row);
        });
        
        // Update mobile cards if found
        if (mobileCards) {
            mobileCards.innerHTML = '';
            settings.forEach(setting => {
                const card = this.createMobileCard(setting);
                mobileCards.appendChild(card);
            });
        }
    }

    createTableRow(setting) {
        const row = document.createElement('tr');
        row.className = 'table-row';
        
        const highlightedKey = this.highlightText(setting.key, this.currentSearchTerm);
        const highlightedDescription = setting.description ? this.highlightText(setting.description, this.currentSearchTerm) : '';
        const highlightedValue = this.highlightText(setting.formatted_value, this.currentSearchTerm);
        
        row.innerHTML = `
            <td class="table-cell">
                <div class="flex items-center">
                    <i class="${setting.type_icon} icon-gray mr-2"></i>
                    <div>
                        <div class="text-sm font-medium text-primary">${highlightedKey}</div>
                        ${highlightedDescription ? `<div class="text-sm text-secondary">${highlightedDescription}</div>` : ''}
                    </div>
                </div>
            </td>
            <td class="table-cell">
                <div class="text-sm text-primary max-w-xs truncate">
                    ${highlightedValue}
                </div>
            </td>
            <td class="table-cell">
                <span class="badge badge-blue">
                    ${setting.type.charAt(0).toUpperCase() + setting.type.slice(1)}
                </span>
            </td>
            <td class="table-cell">
                <span class="badge badge-gray">
                    ${setting.group_name}
                </span>
            </td>
            <td class="table-cell">
                <span class="badge ${setting.is_active ? 'status-active' : 'status-inactive'}">
                    ${setting.is_active ? 'เปิดใช้งาน' : 'ปิดใช้งาน'}
                </span>
            </td>
            <td class="table-cell text-sm font-medium">
                <div class="flex items-center space-actions">
                    <a href="/backend/settings-general/${setting.id}" 
                       class="btn-action btn-action-view"
                       title="ดูรายละเอียด">
                        <i class="fas fa-eye icon-sm"></i>
                    </a>
                    <button type="button" 
                            onclick="settingsManager.openEditModal(${setting.id})"
                            class="btn-action btn-action-edit"
                            title="แก้ไข">
                        <i class="fas fa-edit icon-sm"></i>
                    </button>
                    ${setting.is_active ? 
                        `<button type="button" onclick="settingsManager.toggleStatus(${setting.id})" class="btn-action btn-action-toggle-off" title="ปิดการใช้งาน"><i class="fas fa-toggle-off icon-sm"></i></button>` :
                        `<button type="button" onclick="settingsManager.toggleStatus(${setting.id})" class="btn-action btn-action-toggle-on" title="เปิดการใช้งาน"><i class="fas fa-toggle-on icon-sm"></i></button>`
                    }
                    ${setting.default_value ? 
                        `<button type="button" onclick="settingsManager.resetSetting(${setting.id})" class="btn-action btn-action-reset" title="รีเซ็ตเป็นค่าเริ่มต้น"><i class="fas fa-undo icon-sm"></i></button>` : ''
                    }
                </div>
            </td>
        `;
        
        return row;
    }

    createMobileCard(setting) {
        const card = document.createElement('div');
        card.className = 'settings-card p-4';
        
        const highlightedKey = this.highlightText(setting.key, this.currentSearchTerm);
        const highlightedDescription = setting.description ? this.highlightText(setting.description, this.currentSearchTerm) : '';
        
        card.innerHTML = `
            <div class="flex items-start justify-between mb-4">
                <div class="flex items-center flex-1">
                    <i class="${setting.type_icon} icon-gray mr-3 icon-lg"></i>
                    <div class="flex-1 min-w-0">
                        <h3 class="text-base font-medium text-primary truncate">${highlightedKey}</h3>
                        ${highlightedDescription ? `<p class="text-sm text-secondary mt-1">${highlightedDescription}</p>` : ''}
                    </div>
                </div>
                <span class="badge ${setting.is_active ? 'status-active' : 'status-inactive'}">
                    ${setting.is_active ? 'เปิด' : 'ปิด'}
                </span>
            </div>
            <div class="flex items-center justify-center space-actions-mobile">
                <a href="/backend/settings-general/${setting.id}" 
                   class="btn-action-sm btn-action-view"
                   title="ดูรายละเอียด">
                    <i class="fas fa-eye"></i>
                </a>
                <button type="button" 
                        onclick="settingsManager.openEditModal(${setting.id})"
                        class="btn-action-sm btn-action-edit"
                        title="แก้ไข">
                    <i class="fas fa-edit"></i>
                </button>
                ${setting.is_active ? 
                    `<button type="button" onclick="settingsManager.toggleStatus(${setting.id})" class="btn-action-sm btn-action-toggle-off" title="ปิดการใช้งาน"><i class="fas fa-toggle-off"></i></button>` :
                    `<button type="button" onclick="settingsManager.toggleStatus(${setting.id})" class="btn-action-sm btn-action-toggle-on" title="เปิดการใช้งาน"><i class="fas fa-toggle-on"></i></button>`
                }
                ${setting.default_value ? 
                    `<button type="button" onclick="settingsManager.resetSetting(${setting.id})" class="btn-action-sm btn-action-reset" title="รีเซ็ตเป็นค่าเริ่มต้น"><i class="fas fa-undo"></i></button>` : ''
                }
            </div>
        `;
        
        return card;
    }

    // Utility Methods
    highlightText(text, searchTerm) {
        if (!searchTerm || !text) return text;
        
        const textStr = String(text);
        const regex = new RegExp(`(${this.escapeRegExp(searchTerm)})`, 'gi');
        return textStr.replace(regex, '<mark class="search-highlight">$1</mark>');
    }

    escapeRegExp(string) {
        return string.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
    }

    async fetchData(url) {
        const response = await fetch(url, {
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        });
        return await response.json();
    }

    getBaseUrl() {
        return window.location.pathname;
    }

    // UI State Management
    showSuggestions(suggestions, searchTerm) {
        const suggestionsContainer = document.getElementById('search-suggestions');
        
        if (!suggestions || suggestions.length === 0) {
            this.hideSuggestions();
            return;
        }
        
        suggestionsContainer.innerHTML = '';
        
        suggestions.forEach(suggestion => {
            const item = document.createElement('div');
            item.className = 'search-suggestion-item';
            item.innerHTML = `
                <div class="flex items-center">
                    <i class="${suggestion.type_icon} icon-gray mr-2"></i>
                    <div class="flex-1">
                        <div class="text-sm font-medium text-primary">${this.highlightText(suggestion.key, searchTerm)}</div>
                        <div class="text-xs text-secondary">${this.highlightText(suggestion.description || '', searchTerm)}</div>
                    </div>
                    <span class="text-xs text-muted">${suggestion.type}</span>
                </div>
            `;
            
            item.addEventListener('click', () => {
                document.getElementById('search').value = suggestion.key;
                this.currentSearchTerm = suggestion.key;
                this.hideSuggestions();
                this.performLiveSearch(suggestion.key, false);
            });
            
            suggestionsContainer.appendChild(item);
        });
        
        suggestionsContainer.classList.remove('hidden');
    }

    hideSuggestions() {
        const suggestionsContainer = document.getElementById('search-suggestions');
        if (suggestionsContainer) {
            suggestionsContainer.classList.add('hidden');
        }
    }

    showSearchLoading() {
        const loadingElement = document.getElementById('search-loading');
        if (loadingElement) {
            loadingElement.classList.remove('hidden');
        }
    }

    hideSearchLoading() {
        const loadingElement = document.getElementById('search-loading');
        if (loadingElement) {
            loadingElement.classList.add('hidden');
        }
    }

    async resetTableToAll() {
        const tbody = document.querySelector('tbody');
        if (!tbody) return;
        
        this.showSearchLoading();
        
        try {
            const response = await this.fetchData(`${this.getBaseUrl()}?ajax=1`);
            this.hideSearchLoading();
            
            if (response.success) {
                this.updateTableWithResults(response.settings);
            } else {
                console.error('Reset failed:', response);
            }
        } catch (error) {
            console.error('Reset error:', error);
            this.hideSearchLoading();
        }
    }

    clearFilters() {
        document.getElementById('search').value = '';
        document.getElementById('status-filter').value = '';
        this.currentSearchTerm = '';
        this.hideSuggestions();
        this.resetTableToAll();
    }

    // Status Toggle Methods
    async toggleStatus(id) {
        const row = document.querySelector(`button[onclick="settingsManager.toggleStatus(${id})"]`).closest('tr');
        const settingKey = row.querySelector('.text-sm.font-medium').textContent;
        const currentStatus = row.querySelector('.bg-green-100, .bg-red-100') ? 
            (row.querySelector('.bg-green-100') ? 'เปิดใช้งาน' : 'ปิดใช้งาน') : 'ไม่ทราบสถานะ';
        
        const confirmData = this.getToggleConfirmData(settingKey, currentStatus);
        
        const result = await Swal.fire({
            title: 'คุณแน่ใจหรือไม่?',
            text: confirmData.text,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: confirmData.buttonText,
            cancelButtonText: 'ยกเลิก'
        });
        
        if (result.isConfirmed) {
            await this.performToggleStatus(id, settingKey, currentStatus);
        }
    }

    getToggleConfirmData(settingKey, currentStatus) {
        const confirmData = {
            text: 'คุณต้องการเปลี่ยนสถานะการตั้งค่านี้หรือไม่?',
            buttonText: 'ใช่, เปลี่ยนสถานะ!'
        };
        
        if (settingKey === 'maintenance_mode') {
            if (currentStatus === 'ปิดใช้งาน') {
                confirmData.text = 'คุณต้องการเปิดโหมดบำรุงรักษาหรือไม่? ระบบจะแสดงหน้า maintenance สำหรับผู้ใช้ทั่วไป';
                confirmData.buttonText = 'ใช่, เปิดโหมดบำรุงรักษา!';
            } else {
                confirmData.text = 'คุณต้องการปิดโหมดบำรุงรักษาหรือไม่? ระบบจะกลับมาทำงานปกติ';
                confirmData.buttonText = 'ใช่, ปิดโหมดบำรุงรักษา!';
            }
        } else if (settingKey === 'debug_mode') {
            if (currentStatus === 'ปิดใช้งาน') {
                confirmData.text = 'คุณต้องการเปิด debug mode หรือไม่? ระบบจะแสดงข้อมูล debug และ error messages รายละเอียด';
                confirmData.buttonText = 'ใช่, เปิด debug mode!';
            } else {
                confirmData.text = 'คุณต้องการปิด debug mode หรือไม่? ระบบจะซ่อนข้อมูล debug และแสดง error messages แบบสั้น';
                confirmData.buttonText = 'ใช่, ปิด debug mode!';
            }
        } else if (settingKey === 'debug_bar') {
            if (currentStatus === 'ปิดใช้งาน') {
                confirmData.text = 'คุณต้องการเปิด debug bar หรือไม่? จะแสดง debug toolbar ที่ด้านล่างหน้าเว็บ';
                confirmData.buttonText = 'ใช่, เปิด debug bar!';
            } else {
                confirmData.text = 'คุณต้องการปิด debug bar หรือไม่? จะซ่อน debug toolbar';
                confirmData.buttonText = 'ใช่, ปิด debug bar!';
            }
        }
        
        return confirmData;
    }

    async performToggleStatus(id, settingKey, currentStatus) {
        const url = `/backend/settings-general/${id}/toggle-status`;
        
        try {
            const response = await fetch(url, {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'X-HTTP-Method-Override': 'PATCH'
                }
            });
            
            const data = await response.json();
            
            if (data.message) {
                const successText = this.getToggleSuccessMessage(settingKey, currentStatus);
                
                await Swal.fire({
                    title: 'สำเร็จ!',
                    text: successText,
                    icon: 'success',
                    confirmButtonText: 'ตกลง'
                });
                
                location.reload();
            } else if (data.success === false) {
                await Swal.fire({
                    title: 'เกิดข้อผิดพลาด!',
                    text: data.message || 'เกิดข้อผิดพลาดในการเปลี่ยนสถานะ',
                    icon: 'error',
                    confirmButtonText: 'ตกลง'
                });
            }
        } catch (error) {
            console.error('Error:', error);
            await Swal.fire({
                title: 'เกิดข้อผิดพลาด!',
                text: 'เกิดข้อผิดพลาดในการเปลี่ยนสถานะ',
                icon: 'error',
                confirmButtonText: 'ตกลง'
            });
        }
    }

    getToggleSuccessMessage(settingKey, currentStatus) {
        if (settingKey === 'maintenance_mode') {
            return currentStatus === 'ปิดใช้งาน' ? 
                'เปิดโหมดบำรุงรักษาเรียบร้อยแล้ว ระบบจะแสดงหน้า maintenance สำหรับผู้ใช้ทั่วไป' :
                'ปิดโหมดบำรุงรักษาเรียบร้อยแล้ว ระบบกลับมาทำงานปกติ';
        } else if (settingKey === 'debug_mode') {
            return currentStatus === 'ปิดใช้งาน' ? 
                'เปิด debug mode เรียบร้อยแล้ว ระบบจะแสดงข้อมูล debug และ error messages รายละเอียด' :
                'ปิด debug mode เรียบร้อยแล้ว ระบบจะซ่อนข้อมูล debug และแสดง error messages แบบสั้น';
        } else if (settingKey === 'debug_bar') {
            return currentStatus === 'ปิดใช้งาน' ? 
                'เปิด debug bar เรียบร้อยแล้ว จะแสดง debug toolbar ที่ด้านล่างหน้าเว็บ' :
                'ปิด debug bar เรียบร้อยแล้ว จะซ่อน debug toolbar';
        }
        
        return 'เปลี่ยนสถานะเรียบร้อยแล้ว';
    }

    // Edit Modal Methods
    async openEditModal(id) {
        try {
            const response = await fetch(`/backend/settings-general/${id}`, {
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            });
            
            const data = await response.json();
            
            this.resetFileUploadSections();
            
            const isFileType = ['site_logo', 'site_favicon'].includes(data.key);
            
            if (isFileType) {
                this.showFileUploadSection();
                if (data.value && data.value !== '') {
                    this.showCurrentFilePreview(data.value);
                }
            } else {
                this.showTextInputSection();
                document.getElementById('edit_value').value = data.value;
            }
            
            this.populateEditForm(data);
            this.showEditModal();
            
        } catch (error) {
            console.error('Error:', error);
            await Swal.fire({
                title: 'เกิดข้อผิดพลาด!',
                text: 'เกิดข้อผิดพลาดในการโหลดข้อมูล',
                icon: 'error',
                confirmButtonText: 'ตกลง'
            });
        }
    }

    populateEditForm(data) {
        document.getElementById('edit_description').value = data.description || '';
        document.getElementById('edit_is_active').checked = data.is_active;

        document.getElementById('edit_key_display').textContent = data.key;
        document.getElementById('edit_type_display').textContent = data.type.charAt(0).toUpperCase() + data.type.slice(1);
        document.getElementById('edit_group_display').textContent = data.group_name.charAt(0).toUpperCase() + data.group_name.slice(1);

        document.getElementById('current_type').textContent = data.type.charAt(0).toUpperCase() + data.type.slice(1);
        document.getElementById('current_group').textContent = data.group_name.charAt(0).toUpperCase() + data.group_name.slice(1);
        document.getElementById('current_value').textContent = data.value;

        document.getElementById('editForm').action = `/backend/settings-general/${data.id}`;
    }

    showEditModal() {
        document.getElementById('editModal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    closeEditModal() {
        document.getElementById('editModal').classList.add('hidden');
        document.body.style.overflow = '';

        document.getElementById('editForm').reset();
        this.resetFileUploadSections();
        this.clearErrors();
    }

    // File Upload Methods
    bindFileUploadEvents() {
        const fileInput = document.getElementById('edit_file');
        if (fileInput) {
            fileInput.addEventListener('change', (e) => this.handleFileChange(e));
        }

        const removeNewFile = document.getElementById('remove_new_file');
        if (removeNewFile) {
            removeNewFile.addEventListener('click', () => this.removeNewFile());
        }

        const removeCurrentFile = document.getElementById('remove_current_file');
        if (removeCurrentFile) {
            removeCurrentFile.addEventListener('click', () => this.removeCurrentFile());
        }
    }

    handleFileChange(e) {
        const file = e.target.files[0];
        if (file) {
            if (!this.validateFile(file)) {
                e.target.value = '';
                return;
            }
            this.showNewFilePreview(file);
        }
    }

    validateFile(file) {
        const allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/x-icon', 'image/vnd.microsoft.icon'];
        if (!allowedTypes.includes(file.type)) {
            Swal.fire({
                title: 'ไฟล์ไม่ถูกต้อง!',
                text: 'กรุณาเลือกไฟล์รูปภาพ (JPG, PNG, GIF, ICO) เท่านั้น',
                icon: 'error',
                confirmButtonText: 'ตกลง'
            });
            return false;
        }
        
        if (file.size > 2 * 1024 * 1024) {
            Swal.fire({
                title: 'ไฟล์ใหญ่เกินไป!',
                text: 'กรุณาเลือกไฟล์ที่มีขนาดไม่เกิน 2MB',
                icon: 'error',
                confirmButtonText: 'ตกลง'
            });
            return false;
        }
        
        return true;
    }

    showFileUploadSection() {
        document.getElementById('file_upload_section').classList.remove('hidden');
        document.getElementById('edit_value').classList.add('hidden');
        document.getElementById('edit_value').removeAttribute('required');
        document.getElementById('edit_value').value = '';
    }

    showTextInputSection() {
        document.getElementById('file_upload_section').classList.add('hidden');
        document.getElementById('edit_value').classList.remove('hidden');
        document.getElementById('edit_value').setAttribute('required', 'required');
    }

    resetFileUploadSections() {
        document.getElementById('file_upload_section').classList.add('hidden');
        document.getElementById('current_file_preview').classList.add('hidden');
        document.getElementById('new_file_preview').classList.add('hidden');
        
        document.getElementById('edit_file').value = '';
        
        document.getElementById('edit_value').classList.remove('hidden');
        document.getElementById('edit_value').setAttribute('required', 'required');
        document.getElementById('edit_value').value = '';
    }

    showCurrentFilePreview(filePath) {
        const preview = document.getElementById('current_file_preview');
        const image = document.getElementById('current_file_image');
        const name = document.getElementById('current_file_name');
        const size = document.getElementById('current_file_size');
        
        image.src = filePath.startsWith('http') ? filePath : `/storage/${filePath}`;
        
        const fileName = filePath.split('/').pop();
        name.textContent = fileName;
        size.textContent = 'ขนาดไม่ทราบ';
        
        preview.classList.remove('hidden');
    }

    showNewFilePreview(file) {
        const preview = document.getElementById('new_file_preview');
        const image = document.getElementById('new_file_image');
        const name = document.getElementById('new_file_name');
        const size = document.getElementById('new_file_size');
        
        const fileURL = URL.createObjectURL(file);
        image.src = fileURL;
        
        name.textContent = file.name;
        size.textContent = this.formatFileSize(file.size);
        
        preview.classList.remove('hidden');
    }

    formatFileSize(bytes) {
        if (bytes === 0) return '0 Bytes';
        const k = 1024;
        const sizes = ['Bytes', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
    }

    removeNewFile() {
        document.getElementById('edit_file').value = '';
        document.getElementById('new_file_preview').classList.add('hidden');
    }

    removeCurrentFile() {
        document.getElementById('current_file_preview').classList.add('hidden');
        document.getElementById('edit_value').value = '';
    }

    // Modal Events
    bindModalEvents() {
        const modal = document.getElementById('editModal');
        if (modal) {
            modal.addEventListener('click', (e) => {
                if (e.target === modal) {
                    this.closeEditModal();
                }
            });
        }

        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && !document.getElementById('editModal').classList.contains('hidden')) {
                this.closeEditModal();
            }
        });
    }

    // Form Submission
    async handleFormSubmit(e) {
        e.preventDefault();

        const isFileType = !document.getElementById('file_upload_section').classList.contains('hidden');
        
        let wasRequired = false;
        if (isFileType) {
            const valueInput = document.getElementById('edit_value');
            wasRequired = valueInput.hasAttribute('required');
            valueInput.removeAttribute('required');
        }

        const formData = new FormData(e.target);
        const url = e.target.action;
        
        if (isFileType) {
            const fileInput = document.getElementById('edit_file');
            if (fileInput.files[0]) {
                formData.append('file', fileInput.files[0]);
                formData.delete('value');
            } else {
                const currentValue = document.getElementById('current_value').textContent || '';
                formData.set('value', currentValue);
            }
            
            const valueInput = document.getElementById('edit_value');
            if (wasRequired) {
                valueInput.setAttribute('required', 'required');
            }
        } else {
            const valueInput = document.getElementById('edit_value');
            if (!valueInput.value.trim()) {
                await Swal.fire({
                    title: 'ข้อมูลไม่ครบถ้วน!',
                    text: 'กรุณากรอกค่าการตั้งค่า',
                    icon: 'error',
                    confirmButtonText: 'ตกลง'
                });
                return;
            }
        }

        try {
            const response = await fetch(url, {
                method: 'POST',
                body: formData,
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            });
            
            if (!response.ok) {
                const text = await response.text();
                throw new Error(`HTTP ${response.status}: ${text}`);
            }
            
            const data = await response.json();
            
            if (data.success) {
                this.closeEditModal();
                location.reload();
            } else {
                this.displayValidationErrors(data.errors);
            }
        } catch (error) {
            console.error('Error:', error);
            await Swal.fire({
                title: 'เกิดข้อผิดพลาด!',
                text: 'เกิดข้อผิดพลาดในการบันทึกข้อมูล',
                icon: 'error',
                confirmButtonText: 'ตกลง'
            });
        }
    }

    displayValidationErrors(errors) {
        if (errors) {
            Object.keys(errors).forEach(field => {
                const errorElement = document.getElementById(field + '_error');
                if (errorElement) {
                    errorElement.textContent = errors[field][0];
                    errorElement.classList.remove('hidden');
                }
            });
        }
    }

    clearErrors() {
        const errorElements = document.querySelectorAll('[id$="_error"]');
        errorElements.forEach(element => {
            element.classList.add('hidden');
            element.textContent = '';
        });
    }

    // Reset Setting
    async resetSetting(id) {
        const result = await Swal.fire({
            title: 'คุณแน่ใจหรือไม่?',
            text: 'คุณต้องการรีเซ็ตการตั้งค่านี้เป็นค่าเริ่มต้นหรือไม่?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'ใช่, รีเซ็ต!',
            cancelButtonText: 'ยกเลิก'
        });
        
        if (result.isConfirmed) {
            try {
                const response = await fetch(`/backend/settings-general/${id}/reset`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                });
                
                if (response.ok) {
                    await Swal.fire({
                        title: 'สำเร็จ!',
                        text: 'รีเซ็ตการตั้งค่าเรียบร้อยแล้ว',
                        icon: 'success',
                        confirmButtonText: 'ตกลง'
                    });
                    location.reload();
                }
            } catch (error) {
                console.error('Error:', error);
                await Swal.fire({
                    title: 'เกิดข้อผิดพลาด!',
                    text: 'เกิดข้อผิดพลาดในการรีเซ็ตการตั้งค่า',
                    icon: 'error',
                    confirmButtonText: 'ตกลง'
                });
            }
        }
    }
}

// Initialize the manager when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    window.settingsManager = new SettingsGeneralManager();
});

// Legacy function support for backward compatibility
function toggleStatus(id) {
    if (window.settingsManager) {
        window.settingsManager.toggleStatus(id);
    }
}

function openEditModal(id) {
    if (window.settingsManager) {
        window.settingsManager.openEditModal(id);
    }
}

function closeEditModal() {
    if (window.settingsManager) {
        window.settingsManager.closeEditModal();
    }
}

function resetSetting(id) {
    if (window.settingsManager) {
        window.settingsManager.resetSetting(id);
    }
}
