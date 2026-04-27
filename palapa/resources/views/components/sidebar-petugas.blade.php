<style>
    .sidebar {
        width: 256px;
        background: var(--surface);
        border-radius: var(--radius-lg, 24px);
        box-shadow: var(--shadow, 0 14px 30px rgba(22, 34, 55, 0.08));
        padding: 20px 16px;
        display: flex;
        flex-direction: column;
        transition: width 0.3s ease, padding 0.3s ease;
        overflow: hidden;
        flex-shrink: 0;
        z-index: 50;
    }
    
    .sidebar.collapsed {
        width: 88px;
        padding: 20px 12px;
        align-items: center;
    }

    .brand {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 20px;
        padding: 0 6px;
        width: 100%;
    }

    .sidebar.collapsed .brand {
        justify-content: center;
        padding: 0;
    }

    .brand img {
        width: 96px;
        height: auto;
        transition: width 0.3s ease;
    }

    .sidebar.collapsed .brand img {
        width: 42px;
    }

    .collapse-btn {
        font-size: 18px;
        color: #a5adba;
        cursor: pointer;
        background: none;
        border: none;
        padding: 4px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .sidebar.collapsed .collapse-btn {
        margin: 0 auto;
    }

    .profile {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 10px;
        border-radius: 14px;
        background: #f8fafc;
        margin-bottom: 20px;
        width: 100%;
        transition: background 0.3s ease;
    }

    .sidebar.collapsed .profile {
        background: transparent;
        padding: 5px;
        justify-content: center;
    }

    .avatar {
        width: 42px;
        height: 42px;
        border-radius: 999px;
        object-fit: cover;
        flex-shrink: 0;
    }

    .profile-info {
        overflow: hidden;
        transition: opacity 0.3s ease;
    }

    .sidebar.collapsed .profile-info {
        display: none;
    }

    .profile-info .name {
        margin: 0;
        font-size: 13px;
        font-weight: 700;
        line-height: 1.3;
        color: var(--text);
    }

    .profile-info .email {
        margin: 0;
        font-size: 11px;
        color: var(--muted, #9aa4b2);
        line-height: 1.3;
    }
    
    .profile-bell {
        margin-left: auto;
        color: #687385;
        background: none;
        border: none;
        cursor: pointer;
    }

    .sidebar.collapsed .profile-bell {
        display: none;
    }

    .menu {
        display: flex;
        flex-direction: column;
        gap: 4px;
        width: 100%;
    }

    .menu a {
        display: flex;
        align-items: center;
        gap: 10px;
        text-decoration: none;
        color: #687385;
        font-size: 13px;
        font-weight: 500;
        padding: 10px 12px;
        border-radius: 10px;
        transition: all 0.2s ease;
        white-space: nowrap;
    }

    .sidebar.collapsed .menu a {
        justify-content: center;
        padding: 10px;
    }

    .menu-text {
        transition: opacity 0.3s ease;
    }

    .sidebar.collapsed .menu-text {
        display: none;
    }

    .menu a:hover {
        background: #edf4ff;
        color: var(--primary-dark, #1267ad);
    }

    .menu a.active {
        background: #e8f2ff;
        color: var(--primary-dark, #1267ad);
        font-weight: 600;
    }

    .menu .icon {
        font-size: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        width: 24px;
    }

    .divider {
        height: 1px;
        background: var(--line, #e7ebf1);
        margin: 10px 0;
        width: 100%;
    }

    .sidebar-footer {
        margin-top: auto;
        padding-top: 16px;
        display: flex;
        flex-direction: column;
        gap: 4px;
        width: 100%;
    }

    .sidebar-footer a, .logout-button {
        display: flex;
        align-items: center;
        gap: 10px;
        text-decoration: none;
        color: #687385;
        font-size: 13px;
        font-weight: 500;
        padding: 10px 12px;
        border-radius: 10px;
        transition: all 0.2s ease;
        background: transparent;
        border: none;
        cursor: pointer;
        width: 100%;
        white-space: nowrap;
        font-family: inherit;
    }

    .sidebar.collapsed .sidebar-footer a, .sidebar.collapsed .logout-button {
        justify-content: center;
        padding: 10px;
    }

    .sidebar-footer a:hover {
        background: #edf4ff;
        color: var(--primary-dark, #1267ad);
    }

    .logout-button:hover {
        background: #fff1f1;
        color: #d04a4a;
    }

    .sidebar-toggle-mobile {
        display: none;
    }

    @media (max-width: 980px) {
        .sidebar {
            width: 100% !important;
            height: auto !important;
            flex-direction: column;
        }
        .sidebar.collapsed {
            width: 100% !important;
        }
        .collapse-btn {
            display: none !important;
        }
        .sidebar-toggle-mobile {
            display: block;
            background: none;
            border: none;
            font-size: 24px;
            cursor: pointer;
            color: var(--primary);
        }
        .menu, .sidebar-footer, .profile {
            display: flex !important;
        }
        .sidebar.collapsed .menu, .sidebar.collapsed .sidebar-footer, .sidebar.collapsed .profile {
            display: none !important;
        }
        .sidebar.collapsed .brand {
            justify-content: space-between;
        }
        .sidebar.collapsed .brand img {
            width: 96px;
        }
    }
</style>

<aside class="sidebar" :class="{ 'collapsed': !sidebarOpen }">
    <div class="brand">
        <img src="{{ asset('images/logo-palapa.png') }}" alt="Logo Palapa">
        <button class="collapse-btn" @click="sidebarOpen = !sidebarOpen">
            <i class="ph ph-caret-left" x-show="sidebarOpen"></i>
            <i class="ph ph-caret-right" x-show="!sidebarOpen" style="display: none;"></i>
        </button>
        <button class="sidebar-toggle-mobile" @click="sidebarOpen = !sidebarOpen">
            <i class="ph ph-list"></i>
        </button>
    </div>

    <div class="profile">
        <img class="avatar" src="https://i.pravatar.cc/96?img=12" alt="Foto Pengguna">
        <div class="profile-info">
            <p class="name">{{ auth()->check() ? auth()->user()->users_name : 'John Smith' }}</p>
            <p class="email">{{ auth()->check() ? auth()->user()->email : 'johnsm1th@gmail.com' }}</p>
        </div>
        <button class="profile-bell">
            <i class="ph ph-bell"></i>
        </button>
    </div>

    <div class="divider"></div>

    <nav class="menu">
        <a class="{{ request()->routeIs('petugas.dashboard') ? 'active' : '' }}" href="{{ route('petugas.dashboard') }}">
            <i class="ph ph-squares-four icon"></i> <span class="menu-text">Beranda</span>
        </a>
        <a href="#">
            <i class="ph ph-user icon"></i> <span class="menu-text">Profil Saya</span>
        </a>
        
        <div class="divider"></div>
        
        <a href="#">
            <i class="ph ph-folder-open icon"></i> <span class="menu-text">Laporan Masuk</span>
        </a>
        <a href="#">
            <i class="ph ph-database icon"></i> <span class="menu-text">Manajemen Data</span>
        </a>
    </nav>

    <div class="sidebar-footer">
        <div class="divider"></div>
        <a href="#">
            <i class="ph ph-question icon"></i> <span class="menu-text">FAQ</span>
        </a>
        <form action="{{ route('logout') }}" method="POST" style="margin: 0; width: 100%;">
            @csrf
            <button class="logout-button" type="submit">
                <i class="ph ph-sign-out icon"></i> <span class="menu-text">Keluar</span>
            </button>
        </form>
    </div>
</aside>