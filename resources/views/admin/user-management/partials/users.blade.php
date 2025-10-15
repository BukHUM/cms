<!-- Users Tab -->
<div id="users">

    <!-- Action Buttons and Filters -->
    <div>
        <div>
            <div>
                <div>
                    <div>
                        <div>
                            <div>
                                @can('users.create')
                                <button data-bs-toggle="modal" data-bs-target="#addUserModal">
                                    <i class="fas fa-plus"></i>
                                    เพิ่มผู้ใช้ใหม่
                                </button>
                                @endcan
                                @can('users.view')
                                <button onclick="exportUsers()">
                                    <i class="fas fa-download"></i>
                                    ส่งออกข้อมูล
                                </button>
                                @endcan
                                <button onclick="refreshUsers()">
                                    <i class="fas fa-sync-alt"></i>
                                    รีเฟรช
                                </button>
                            </div>
                        </div>
                        <div>
                            <div>
                                <div>
                                    <div>
                                        <span>
                                            <i class="fas fa-search"></i>
                                        </span>
                                        <input type="text" placeholder="ค้นหาผู้ใช้..." id="usersSearchInput">
                                    </div>
                                </div>
                                <div>
                                    <button type="button" data-bs-toggle="dropdown">
                                        <i class="fas fa-filter"></i>สถานะ
                                    </button>
                                    <ul>
                                        <li><a href="#" onclick="event.preventDefault(); filterByStatus(''); return false;">
                                            <i class="fas fa-list"></i>ทุกสถานะ
                                        </a></li>
                                        <li><hr></li>
                                        <li><a href="#" onclick="event.preventDefault(); filterByStatus('active'); return false;">
                                            <i class="fas fa-check-circle"></i>ใช้งาน
                                        </a></li>
                                        <li><a href="#" onclick="event.preventDefault(); filterByStatus('inactive'); return false;">
                                            <i class="fas fa-times-circle"></i>ไม่ใช้งาน
                                        </a></li>
                                        <li><a href="#" onclick="event.preventDefault(); filterByStatus('pending'); return false;">
                                            <i class="fas fa-clock"></i>รอการยืนยัน
                                        </a></li>
                                        <li><a href="#" onclick="event.preventDefault(); filterByStatus('suspended'); return false;">
                                            <i class="fas fa-ban"></i>ระงับการใช้งาน
                                        </a></li>
                                    </ul>
                                </div>
                                <div>
                                    <button type="button" data-bs-toggle="dropdown">
                                        <i class="fas fa-user-shield"></i>บทบาท
                                    </button>
                                    <ul>
                                        <li><a href="#" onclick="event.preventDefault(); filterByRole(''); return false;">
                                            <i class="fas fa-list"></i>ทุกบทบาท
                                        </a></li>
                                        <li><hr></li>
                                        @foreach(\App\Models\Role::active()->ordered()->get() as $role)
                                        <li><a href="#" onclick="event.preventDefault(); filterByRole('{{ $role->id }}'); return false;">
                                            <span>{{ $role->name }}</span>
                                            {{ $role->name }}
                                        </a></li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Users Table -->
    <div>
        <div>
            <h6>
                <i class="fas fa-users"></i>
                รายชื่อผู้ใช้
                <span id="userCount">{{ $users->count() }}</span>
            </h6>
            <div>
                <button onclick="toggleViewMode()" id="viewModeBtn">
                    <i class="fas fa-th-large"></i>
                </button>
                <div>
                    <button type="button" data-bs-toggle="dropdown">
                        <i class="fas fa-cog"></i>
                    </button>
                    <ul>
                        <li><a href="#" onclick="selectAllUsers()">
                            <i class="fas fa-check-square"></i>เลือกทั้งหมด
                        </a></li>
                        <li><a href="#" onclick="clearSelection()">
                            <i class="fas fa-square"></i>ยกเลิกการเลือก
                        </a></li>
                        <li><hr></li>
                        <li><a href="#" onclick="bulkStatusUpdate('active')">
                            <i class="fas fa-check"></i>เปลี่ยนเป็นใช้งาน
                        </a></li>
                        <li><a href="#" onclick="bulkStatusUpdate('inactive')">
                            <i class="fas fa-times"></i>เปลี่ยนเป็นไม่ใช้งาน
                        </a></li>
                    </ul>
                </div>
            </div>
        </div>
        <div>
            <div>
                <table id="usersTable">
                    <thead>
                        <tr>
                            <th>
                                <input type="checkbox" id="selectAll" onchange="toggleSelectAll()">
                            </th>
                            <th>ผู้ใช้</th>
                            <th>อีเมล</th>
                            <th>บทบาท</th>
                            <th>สถานะ</th>
                            <th>วันที่สมัคร</th>
                            <th>การดำเนินการ</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $user)
                        <tr data-user-id="{{ $user->id }}" data-status="{{ $user->status }}">
                            <td>
                                <input type="checkbox" value="{{ $user->id }}" onchange="updateBulkActions()">
                            </td>
                            <td>
                                <div>
                                    <div>
                                        <img src="{{ $user->avatar ? asset('storage/' . $user->avatar) : 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&background=6c757d&color=fff' }}" 
                                             alt="User">
                                        @if($user->status === 'active')
                                        <span></span>
                                        @elseif($user->status === 'pending')
                                        <span></span>
                                        @elseif($user->status === 'suspended')
                                        <span></span>
                                        @endif
                                    </div>
                                    <div>
                                        <div>{{ $user->name }}</div>
                                        @if($user->phone)
                                        <small>
                                            <i class="fas fa-phone"></i>{{ $user->phone }}
                                        </small>
                                        @endif
                                        @if($user->last_login_at)
                                        <small>
                                            <i class="fas fa-clock"></i>เข้าสู่ระบบล่าสุด: {{ $user->last_login_at->diffForHumans() }}
                                        </small>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div>
                                    <i class="fas fa-envelope"></i>
                                    <span>{{ $user->email }}</span>
                                    @if($user->email_verified_at)
                                    <i class="fas fa-check-circle" title="ยืนยันอีเมลแล้ว"></i>
                                    @else
                                    <i class="fas fa-exclamation-circle" title="ยังไม่ยืนยันอีเมล"></i>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <div>
                                    @foreach($user->roles as $role)
                                        <span title="{{ $role->description }}">
                                            {{ $role->name }}
                                        </span>
                                    @endforeach
                                </div>
                            </td>
                            <td>
                                <div>
                                    <button type="button" data-bs-toggle="dropdown">
                                        {{ $user->getStatusDisplayName() }}
                                    </button>
                                    <ul>
                                        <li><a href="#" onclick="updateUserStatus({{ $user->id }}, 'active')">
                                            <i class="fas fa-check"></i>ใช้งาน
                                        </a></li>
                                        <li><a href="#" onclick="updateUserStatus({{ $user->id }}, 'inactive')">
                                            <i class="fas fa-times"></i>ไม่ใช้งาน
                                        </a></li>
                                        <li><a href="#" onclick="updateUserStatus({{ $user->id }}, 'pending')">
                                            <i class="fas fa-clock"></i>รอการยืนยัน
                                        </a></li>
                                        <li><a href="#" onclick="updateUserStatus({{ $user->id }}, 'suspended')">
                                            <i class="fas fa-ban"></i>ระงับการใช้งาน
                                        </a></li>
                                    </ul>
                                </div>
                            </td>
                            <td>
                                <small>
                                    {{ $user->created_at->format('d/m/Y') }}<br>
                                    <i class="fas fa-clock"></i>{{ $user->created_at->diffForHumans() }}
                                </small>
                            </td>
                            <td>
                                <div>
                                    @can('users.view')
                                    <button onclick="viewUser({{ $user->id }})" title="ดูรายละเอียด">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    @endcan
                                    @can('users.update')
                                    <button onclick="editUser({{ $user->id }})" title="แก้ไข">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    @endcan
                                    @can('users.roles')
                                    <button onclick="manageUserRoles({{ $user->id }})" title="จัดการบทบาท">
                                        <i class="fas fa-user-shield"></i>
                                    </button>
                                    @endcan
                                    <div>
                                        <button type="button" data-bs-toggle="dropdown">
                                            <i class="fas fa-ellipsis-v"></i>
                                        </button>
                                        <ul>
                                            @can('users.view')
                                            <li><a href="#" onclick="viewUser({{ $user->id }})">
                                                <i class="fas fa-eye"></i>ดูรายละเอียด
                                            </a></li>
                                            @endcan
                                            @can('users.update')
                                            <li><a href="#" onclick="editUser({{ $user->id }})">
                                                <i class="fas fa-edit"></i>แก้ไขข้อมูล
                                            </a></li>
                                            @endcan
                                            @can('users.roles')
                                            <li><a href="#" onclick="manageUserRoles({{ $user->id }})">
                                                <i class="fas fa-user-shield"></i>จัดการบทบาท
                                            </a></li>
                                            @endcan
                                            @can('users.delete')
                                            @if($user->id !== auth()->id())
                                            <li><hr></li>
                                            <li><a href="#" onclick="deleteUser({{ $user->id }})">
                                                <i class="fas fa-trash"></i>ลบผู้ใช้
                                            </a></li>
                                            @endif
                                            @endcan
                                        </ul>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7">
                                <div>
                                    <i class="fas fa-users"></i>
                                    <h5>ไม่พบข้อมูลผู้ใช้</h5>
                                    <p>ยังไม่มีผู้ใช้ในระบบ หรือไม่ตรงกับเงื่อนไขการค้นหา</p>
                                    @can('users.create')
                                    <button data-bs-toggle="modal" data-bs-target="#addUserModal">
                                        <i class="fas fa-plus"></i>เพิ่มผู้ใช้คนแรก
                                    </button>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Pagination -->
    @if($users->hasPages())
    <div>
        {{ $users->links() }}
    </div>
    @endif
</div>

<!-- Add User Modal -->
@can('users.create')
<div id="addUserModal" tabindex="-1">
    <div>
        <div>
            <div>
                <h5>
                    <i class="fas fa-user-plus"></i>
                    เพิ่มผู้ใช้ใหม่
                </h5>
                <button type="button" data-bs-dismiss="modal"></button>
            </div>
            <form id="addUserForm">
                <div>
                    <div>
                        <div>
                            <div>
                                <div>
                                    <label for="firstName">
                                        <i class="fas fa-user"></i>ชื่อ *
                                    </label>
                                    <input type="text" id="firstName" required placeholder="กรอกชื่อ">
                                </div>
                                <div>
                                    <label for="lastName">
                                        <i class="fas fa-user"></i>นามสกุล *
                                    </label>
                                    <input type="text" id="lastName" required placeholder="กรอกนามสกุล">
                                </div>
                                <div>
                                    <label for="email">
                                        <i class="fas fa-envelope"></i>อีเมล *
                                    </label>
                                    <input type="email" id="email" required placeholder="example@domain.com">
                                    <div>อีเมลจะใช้สำหรับเข้าสู่ระบบ</div>
                                </div>
                                <div>
                                    <label for="phone">
                                        <i class="fas fa-phone"></i>เบอร์โทรศัพท์
                                    </label>
                                    <input type="tel" id="phone" placeholder="08x-xxx-xxxx">
                                </div>
                                <div class="col-md-6">
                                    <label for="roles" >
                                        <i class="fas fa-user-shield me-1"></i>บทบาท *
                                    </label>
                                    <select  id="roles" multiple required>
                                        @foreach(\App\Models\Role::active()->ordered()->get() as $role)
                                            <option value="{{ $role->id }}">{{ $role->name }}</option>
                                        @endforeach
                                    </select>
                                    <div >เลือกบทบาทที่ต้องการมอบหมาย</div>
                                </div>
                                <div class="col-md-6">
                                    <label for="status" >
                                        <i class="fas fa-toggle-on me-1"></i>สถานะ *
                                    </label>
                                    <select  id="status" required>
                                        <option value="active">ใช้งาน</option>
                                        <option value="inactive">ไม่ใช้งาน</option>
                                        <option value="pending">รอการยืนยัน</option>
                                        <option value="suspended">ระงับการใช้งาน</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label for="password" >
                                        <i class="fas fa-lock me-1"></i>รหัสผ่าน *
                                    </label>
                                    <div class="">
                                        <input type="password"  id="password" required placeholder="รหัสผ่านอย่างน้อย 8 ตัวอักษร">
                                        <button class=" btn-outline-secondary" type="button" onclick="togglePassword('password')">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                    <div >รหัสผ่านต้องมีอย่างน้อย 8 ตัวอักษร</div>
                                </div>
                                <div class="col-md-6">
                                    <label for="password_confirmation" >
                                        <i class="fas fa-lock me-1"></i>ยืนยันรหัสผ่าน *
                                    </label>
                                    <div class="">
                                        <input type="password"  id="password_confirmation" required placeholder="ยืนยันรหัสผ่าน">
                                        <button class=" btn-outline-secondary" type="button" onclick="togglePassword('password_confirmation')">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="">
                                <div class="-header">
                                    <h6 class="mb-0">
                                        <i class="fas fa-info-circle me-2"></i>
                                        ข้อมูลเพิ่มเติม
                                    </h6>
                                </div>
                                <div class="-body">
                                    <div class="mb-3">
                                        <label >รูปโปรไฟล์</label>
                                        <div class="text-center">
                                            <img id="avatarPreview" src="https://ui-avatars.com/api/?name=User&background=6c757d&color=fff" 
                                                 class="rounded-circle mb-2">
                                            <div>
                                                <button type="button" class=" btn-sm btn-outline-primary" onclick="document.getElementById('avatar').click()">
                                                    <i class="fas fa-camera me-1"></i>เลือกรูป
                                                </button>
                                                <input type="file" id="avatar" accept="image/*" class="d-none" onchange="previewAvatar(this)">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label >หมายเหตุ</label>
                                        <textarea  id="notes" rows="3" placeholder="หมายเหตุเพิ่มเติม (ถ้ามี)"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="footer">
                    <button type="button" class=" btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i>ยกเลิก
                    </button>
                    <button type="submit" class=" btn-primary">
                        <i class="fas fa-save me-1"></i>เพิ่มผู้ใช้
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endcan

<!-- Manage User Roles Modal -->
@can('users.roles')
<div class="modal fade" id="manageUserRolesModal" tabindex="-1">
    <div class="dialog modal-lg">
        <div class="content">
            <div class="header">
                <h5 class="title">จัดการบทบาทผู้ใช้</h5>
                <button type="button" class="-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="manageUserRolesForm">
                <div class="body">
                    <div id="userInfo" class="mb-3"></div>
                    <div class="row g-3">
                        <div class="col-12">
                            <label >เลือกบทบาท</label>
                            <div id="rolesContainer"></div>
                        </div>
                    </div>
                </div>
                <div class="footer">
                    <button type="button" class=" btn-secondary" data-bs-dismiss="modal">ยกเลิก</button>
                    <button type="submit" class=" btn-primary">บันทึกบทบาท</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endcan
