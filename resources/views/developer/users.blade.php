@extends('layouts.app')

@section('title', 'User Management - Developer Dashboard')

@section('content')
<div class="developer-container">
    <div class="developer-content">
        <!-- Header Section -->
        <div class="developer-header">
            <div class="header-content">
                <h1 class="neon-text">User Management</h1>
                <p class="neon-subtext">Manage all system users and their permissions</p>
            </div>
            <div class="header-actions">
                <button type="button" class="create-user-btn" onclick="openModal('createUserModal')">
                    <i class="fas fa-plus"></i>
                    <span>Create New User</span>
                </button>
            </div>
        </div>

        <!-- Success/Error Messages -->
        @if(session('success'))
            <div class="notification success-notification">
                <i class="fas fa-check-circle"></i>
                <span>{{ session('success') }}</span>
                <button type="button" class="notification-close" onclick="closeNotification(this)">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        @endif

        @if(session('error'))
            <div class="notification error-notification">
                <i class="fas fa-exclamation-triangle"></i>
                <span>{{ session('error') }}</span>
                <button type="button" class="notification-close" onclick="closeNotification(this)">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        @endif

        <!-- Users Table -->
        <div class="users-table-container">
            <div class="table-header">
                <h3>System Users</h3>
                <div class="table-stats">
                    <span class="stat-item">Total: {{ $users->total() }}</span>
                </div>
            </div>

            <div class="users-table">
                <div class="table-head">
                    <div class="table-row">
                        <div class="table-cell">ID</div>
                        <div class="table-cell">User</div>
                        <div class="table-cell">Email</div>
                        <div class="table-cell">Role</div>
                        <div class="table-cell">Products</div>
                        <div class="table-cell">Created</div>
                        <div class="table-cell">Actions</div>
                    </div>
                </div>
                <div class="table-body">
                    @forelse($users as $user)
                    <div class="table-row">
                        <div class="table-cell">{{ $user->id }}</div>
                        <div class="table-cell">
                            <div class="user-info">
                                <div class="user-avatar">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                                <span class="user-name">{{ $user->name }}</span>
                            </div>
                        </div>
                        <div class="table-cell">{{ $user->email }}</div>
                        <div class="table-cell">
                            <span class="role-badge
                                @if($user->role === 'developer') role-developer
                                @elseif($user->role === 'seller') role-seller
                                @else role-user
                                @endif">
                                {{ ucfirst($user->role) }}
                            </span>
                        </div>
                        <div class="table-cell">
                            @if($user->role === 'seller')
                                <span class="product-count">{{ $user->products->count() }} products</span>
                            @else
                                <span class="no-data">-</span>
                            @endif
                        </div>
                        <div class="table-cell">{{ $user->created_at->format('M d, Y') }}</div>
                        <div class="table-cell">
                            <div class="action-buttons">
                                <!-- Edit Role Button -->
                                <button type="button" class="action-btn edit-btn"
                                        onclick="openEditModal({{ $user->id }}, '{{ $user->name }}', '{{ $user->role }}')">
                                    <i class="fas fa-edit"></i>
                                    <span>Edit Role</span>
                                </button>

                                <!-- Delete Button (disabled for current user) -->
                                @if($user->id !== auth()->id())
                                <button type="button" class="action-btn delete-btn"
                                        onclick="openDeleteModal({{ $user->id }}, '{{ $user->name }}')">
                                    <i class="fas fa-trash"></i>
                                    <span>Delete</span>
                                </button>
                                @else
                                <button type="button" class="action-btn disabled-btn" disabled>
                                    <i class="fas fa-user-shield"></i>
                                    <span>Current User</span>
                                </button>
                                @endif
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="empty-state">
                        <i class="fas fa-users"></i>
                        <h3>No Users Found</h3>
                        <p>No users are currently registered in the system.</p>
                    </div>
                    @endforelse
                </div>
            </div>

            <!-- Pagination -->
            @if($users->hasPages())
            <div class="pagination-container">
                {{ $users->links() }}
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Create User Modal -->
<div id="createUserModal" class="custom-modal">
    <div class="modal-overlay" onclick="closeModal('createUserModal')"></div>
    <div class="modal-content">
        <div class="modal-header">
            <h3 class="modal-title">
                <i class="fas fa-user-plus"></i>
                Create New User
            </h3>
            <button type="button" class="modal-close" onclick="closeModal('createUserModal')">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <form action="{{ route('developer.users.create') }}" method="POST" class="modal-form">
            @csrf
            <div class="modal-body">
                <div class="form-group">
                    <label for="name" class="form-label">Name</label>
                    <input type="text" class="form-input" id="name" name="name" required>
                </div>
                <div class="form-group">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-input" id="email" name="email" required>
                </div>
                <div class="form-group">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-input" id="password" name="password" required>
                </div>
                <div class="form-group">
                    <label for="role" class="form-label">Role</label>
                    <select class="form-select" id="role" name="role" required>
                        <option value="user">User</option>
                        <option value="seller">Seller</option>
                        <option value="developer">Developer</option>
                    </select>
                </div>
            </div>
            <div class="modal-actions">
                <button type="button" class="btn-secondary" onclick="closeModal('createUserModal')">Cancel</button>
                <button type="submit" class="btn-primary">Create User</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Role Modal -->
<div id="editRoleModal" class="custom-modal">
    <div class="modal-overlay" onclick="closeModal('editRoleModal')"></div>
    <div class="modal-content">
        <div class="modal-header">
            <h3 class="modal-title">
                <i class="fas fa-edit"></i>
                Edit User Role
            </h3>
            <button type="button" class="modal-close" onclick="closeModal('editRoleModal')">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <form id="editRoleForm" method="POST" class="modal-form">
            @csrf
            @method('PATCH')
            <div class="modal-body">
                <div class="form-info">
                    <p>Change role for user: <strong id="editUserName"></strong></p>
                </div>
                <div class="form-group">
                    <label for="editRole" class="form-label">New Role</label>
                    <select class="form-select" id="editRole" name="role" required>
                        <option value="user">User</option>
                        <option value="seller">Seller</option>
                        <option value="developer">Developer</option>
                    </select>
                </div>
            </div>
            <div class="modal-actions">
                <button type="button" class="btn-secondary" onclick="closeModal('editRoleModal')">Cancel</button>
                <button type="submit" class="btn-primary">Update Role</button>
            </div>
        </form>
    </div>
</div>

<!-- Delete User Modal -->
<div id="deleteUserModal" class="custom-modal">
    <div class="modal-overlay" onclick="closeModal('deleteUserModal')"></div>
    <div class="modal-content">
        <div class="modal-header">
            <h3 class="modal-title danger">
                <i class="fas fa-exclamation-triangle"></i>
                Delete User
            </h3>
            <button type="button" class="modal-close" onclick="closeModal('deleteUserModal')">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="modal-body">
            <div class="form-info">
                <p>Are you sure you want to delete user: <strong id="deleteUserName"></strong>?</p>
            </div>
            <div class="warning-message">
                <i class="fas fa-exclamation-triangle"></i>
                <div class="warning-content">
                    <strong>Warning:</strong> This action cannot be undone. All user data will be permanently deleted.
                </div>
            </div>
        </div>
        <div class="modal-actions">
            <button type="button" class="btn-secondary" onclick="closeModal('deleteUserModal')">Cancel</button>
            <form id="deleteUserForm" method="POST" style="display: inline;">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn-danger">Delete User</button>
            </form>
        </div>
    </div>
</div>

<style>
/* Developer Container */
.developer-container {
    min-height: 100vh;
    background: black;
    padding: 20px;
}

.developer-content {
    max-width: 1400px;
    margin: 150px auto;
}

/* Header Section */
.developer-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 30px;
    padding: 20px;
    background: rgba(255, 255, 255, 0.05);
    border-radius: 15px;
    border: 1px solid rgba(255, 255, 255, 0.1);
    backdrop-filter: blur(10px);
}

.header-content h1 {
    margin: 0;
    font-size: 2.5rem;
    font-weight: 700;
    color: #ffffff;
    text-shadow: 0 0 30px rgba(255, 255, 255, 0.3);
}

.header-content p {
    margin: 5px 0 0 0;
    color: rgba(255, 255, 255, 0.7);
    font-size: 1.1rem;
}

.create-user-btn {
    background: #ffffff;
    border: 2px solid #ffffff;
    padding: 12px 24px;
    border-radius: 10px;
    color: #000000;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    gap: 8px;
    box-shadow: 0 4px 15px rgba(255, 255, 255, 0.2);
}

.create-user-btn:hover {
    background: #000000;
    color: #ffffff;
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(255, 255, 255, 0.3);
}

/* Notifications */
.notification {
    padding: 15px 20px;
    border-radius: 10px;
    margin-bottom: 20px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.1);
}

.success-notification {
    background: rgba(40, 167, 69, 0.2);
    border-left: 4px solid #28a745;
    color: #d4edda;
}

.error-notification {
    background: rgba(220, 53, 69, 0.2);
    border-left: 4px solid #dc3545;
    color: #f8d7da;
}

.notification-close {
    background: none;
    border: none;
    color: inherit;
    cursor: pointer;
    font-size: 18px;
    padding: 0;
}

/* Users Table Container */
.users-table-container {
    background: rgba(255, 255, 255, 0.05);
    border-radius: 15px;
    border: 1px solid rgba(255, 255, 255, 0.1);
    backdrop-filter: blur(10px);
    overflow: hidden;
}

.table-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 20px 25px;
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
}

.table-header h3 {
    margin: 0;
    color: white;
    font-size: 1.5rem;
    font-weight: 600;
}

.table-stats {
    display: flex;
    gap: 15px;
}

.stat-item {
    background: rgba(255, 255, 255, 0.1);
    padding: 5px 12px;
    border-radius: 20px;
    font-size: 0.9rem;
    color: rgba(255, 255, 255, 0.8);
}

/* Custom Table */
.users-table {
    width: 100%;
}

.table-head {
    background: rgba(255, 255, 255, 0.1);
}

.table-row {
    display: flex;
    border-bottom: 1px solid rgba(255, 255, 255, 0.05);
}

.table-row:last-child {
    border-bottom: none;
}

.table-cell {
    flex: 1;
    padding: 15px 20px;
    color: white;
    font-size: 0.9rem;
    display: flex;
    align-items: center;
}

.table-head .table-cell {
    font-weight: 600;
    color: rgba(255, 255, 255, 0.9);
    text-transform: uppercase;
    font-size: 0.8rem;
    letter-spacing: 0.5px;
}

/* User Info */
.user-info {
    display: flex;
    align-items: center;
    gap: 12px;
}

.user-avatar {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: #ffffff;
    color: #000000;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    font-size: 16px;
    box-shadow: 0 2px 10px rgba(255, 255, 255, 0.3);
}

.user-name {
    font-weight: 500;
}

/* Role Badges */
.role-badge {
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.role-developer {
    background: #ffffff;
    color: #000000;
}

.role-seller {
    background: #ffffff;
    color: #000000;
}

.role-user {
    background: #ffffff;
    color: #000000;
}

/* Product Count */
.product-count {
    color: rgba(255, 255, 255, 0.8);
    font-size: 0.9rem;
}

.no-data {
    color: rgba(255, 255, 255, 0.4);
    font-style: italic;
}

/* Action Buttons */
.action-buttons {
    display: flex;
    gap: 8px;
}

.action-btn {
    padding: 8px 12px;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    font-size: 0.8rem;
    font-weight: 500;
    display: flex;
    align-items: center;
    gap: 6px;
    transition: all 0.3s ease;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.edit-btn {
    background: #ffffff;
    color: #000000;
}

.edit-btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 15px rgba(255, 255, 255, 0.4);
}

.delete-btn {
    background: #ffffff;
    color: #000000;
}

.delete-btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 15px rgba(255, 255, 255, 0.4);
}

.disabled-btn {
    background: rgba(255, 255, 255, 0.1);
    color: rgba(255, 255, 255, 0.4);
    cursor: not-allowed;
}

/* Empty State */
.empty-state {
    text-align: center;
    padding: 60px 20px;
    color: rgba(255, 255, 255, 0.6);
}

.empty-state i {
    font-size: 4rem;
    margin-bottom: 20px;
    opacity: 0.5;
}

.empty-state h3 {
    margin: 0 0 10px 0;
    font-size: 1.5rem;
    color: rgba(255, 255, 255, 0.8);
}

.empty-state p {
    margin: 0;
    font-size: 1rem;
}

/* Pagination */
.pagination-container {
    padding: 20px 25px;
    border-top: 1px solid rgba(255, 255, 255, 0.1);
    display: flex;
    justify-content: center;
}

/* Custom Modals */
.custom-modal {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: 1000;
}

.custom-modal.active {
    display: flex;
    align-items: center;
    justify-content: center;
}

.modal-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.8);
    backdrop-filter: blur(5px);
}

.modal-content {
    background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
    border-radius: 15px;
    border: 1px solid rgba(255, 255, 255, 0.1);
    width: 90%;
    max-width: 500px;
    max-height: 90vh;
    overflow-y: auto;
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.5);
    position: relative;
    z-index: 1001;
}

.modal-header {
    padding: 20px 25px;
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.modal-title {
    margin: 0;
    color: white;
    font-size: 1.3rem;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 10px;
}

.modal-title.danger {
    color: #ff6b6b;
}

.modal-close {
    background: none;
    border: none;
    color: rgba(255, 255, 255, 0.6);
    font-size: 20px;
    cursor: pointer;
    padding: 5px;
    border-radius: 50%;
    transition: all 0.3s ease;
}

.modal-close:hover {
    background: rgba(255, 255, 255, 0.1);
    color: white;
}

.modal-form {
    display: flex;
    flex-direction: column;
}

.modal-body {
    padding: 25px;
}

.form-group {
    margin-bottom: 20px;
}

.form-label {
    display: block;
    margin-bottom: 8px;
    color: rgba(255, 255, 255, 0.9);
    font-weight: 500;
    font-size: 0.9rem;
}

.form-input, .form-select {
    width: 100%;
    padding: 12px 15px;
    border: 1px solid rgba(255, 255, 255, 0.2);
    border-radius: 8px;
    background: rgba(255, 255, 255, 0.05);
    color: white;
    font-size: 1rem;
    transition: all 0.3s ease;
}

.form-input:focus, .form-select:focus {
    outline: none;
    border-color: #667eea;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.2);
}

.form-info {
    margin-bottom: 20px;
}

.form-info p {
    margin: 0;
    color: rgba(255, 255, 255, 0.8);
    font-size: 1rem;
}

.warning-message {
    background: rgba(255, 193, 7, 0.1);
    border: 1px solid rgba(255, 193, 7, 0.3);
    border-radius: 8px;
    padding: 15px;
    display: flex;
    gap: 12px;
    align-items: flex-start;
}

.warning-message i {
    color: #ffc107;
    font-size: 1.2rem;
    margin-top: 2px;
}

.warning-content {
    color: #ffeaa7;
    font-size: 0.9rem;
    line-height: 1.5;
}

.modal-actions {
    padding: 20px 25px;
    border-top: 1px solid rgba(255, 255, 255, 0.1);
    display: flex;
    justify-content: flex-end;
    gap: 12px;
}

.btn-secondary, .btn-primary, .btn-danger {
    padding: 10px 20px;
    border: none;
    border-radius: 8px;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.3s ease;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    font-size: 0.9rem;
}

.btn-secondary {
    background: rgba(255, 255, 255, 0.1);
    color: rgba(255, 255, 255, 0.8);
    border: 1px solid rgba(255, 255, 255, 0.2);
}

.btn-secondary:hover {
    background: rgba(255, 255, 255, 0.2);
    color: white;
}

.btn-primary {
    background: #ffffff;
    color: #000000;
    box-shadow: 0 4px 15px rgba(255, 255, 255, 0.3);
}

.btn-primary:hover {
    transform: translateY(-1px);
    box-shadow: 0 6px 20px rgba(255, 255, 255, 0.4);
}

.btn-danger {
    background: #ffffff;
    color: #000000;
    box-shadow: 0 4px 15px rgba(255, 255, 255, 0.3);
}

.btn-danger:hover {
    transform: translateY(-1px);
    box-shadow: 0 6px 20px rgba(255, 255, 255, 0.4);
}

/* Responsive Design */
@media (max-width: 768px) {
    .developer-header {
        flex-direction: column;
        gap: 20px;
        text-align: center;
    }

    .table-row {
        flex-direction: column;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        padding: 15px;
    }

    .table-cell {
        justify-content: space-between;
        padding: 8px 0;
    }

    .table-cell:before {
        content: attr(data-label);
        font-weight: 600;
        color: rgba(255, 255, 255, 0.6);
        text-transform: uppercase;
        font-size: 0.8rem;
    }

    .modal-content {
        width: 95%;
        margin: 20px;
    }

    .action-buttons {
        flex-direction: column;
        width: 100%;
    }

    .action-btn {
        justify-content: center;
        width: 100%;
    }
}
</style>

<script>
// Modal Functions
function openModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.classList.add('active');
        document.body.style.overflow = 'hidden';
    }
}

function closeModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.classList.remove('active');
        document.body.style.overflow = 'auto';
    }
}

// Close modal when clicking outside
document.addEventListener('click', function(event) {
    if (event.target.classList.contains('modal-overlay')) {
        const modal = event.target.closest('.custom-modal');
        if (modal) {
            closeModal(modal.id);
        }
    }
});

// Close modal with ESC key
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        const activeModal = document.querySelector('.custom-modal.active');
        if (activeModal) {
            closeModal(activeModal.id);
        }
    }
});

// Notification close function
function closeNotification(button) {
    const notification = button.closest('.notification');
    if (notification) {
        notification.style.opacity = '0';
        setTimeout(() => {
            notification.remove();
        }, 300);
    }
}

// Modal specific functions
function openEditModal(userId, userName, userRole) {
    document.getElementById('editUserName').textContent = userName;
    document.getElementById('editRole').value = userRole;
    document.getElementById('editRoleForm').action = `/developer/users/${userId}/role`;
    openModal('editRoleModal');
}

function openDeleteModal(userId, userName) {
    document.getElementById('deleteUserName').textContent = userName;
    document.getElementById('deleteUserForm').action = `/developer/users/${userId}`;
    openModal('deleteUserModal');
}

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    // Add data-label attributes for mobile responsiveness
    const tableCells = document.querySelectorAll('.table-body .table-cell');
    const headers = ['ID', 'User', 'Email', 'Role', 'Products', 'Created', 'Actions'];

    tableCells.forEach((cell, index) => {
        const columnIndex = index % headers.length;
        cell.setAttribute('data-label', headers[columnIndex]);
    });
});
</script>
@endsection
