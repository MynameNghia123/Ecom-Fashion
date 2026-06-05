<template>
  <div class="admin-layout">
    <!-- Sidebar -->
    <Sidebar />

    <!-- Main Area -->
    <div class="admin-main">

      <!-- Topbar -->
      <header class="admin-topbar">
        <!-- Breadcrumb -->
        <div class="topbar-breadcrumb">
          <span class="breadcrumb-item">Admin</span>
          <svg class="breadcrumb-sep" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
            <polyline points="9 18 15 12 9 6"/>
          </svg>
          <span class="breadcrumb-current">Bảng tổng quan</span>
        </div>

        <!-- Topbar Right Actions -->
        <div class="topbar-actions">
          <!-- System Status Pill -->
          <div class="status-pill">
            <span class="status-dot"></span>
            <span>Hệ thống ổn định</span>
          </div>

          <!-- Notification Bell -->
          <button class="topbar-btn" title="Thông báo">
            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
              <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/>
              <path d="M13.73 21a2 2 0 0 1-3.46 0"/>
            </svg>
            <span class="notif-dot"></span>
          </button>

          <!-- Admin User -->
          <div class="topbar-user">
            <span class="topbar-username">Trần Nghĩa</span>
            <div class="topbar-avatar">TN</div>
          </div>
        </div>
      </header>

      <!-- Page Content -->
      <main class="admin-content">
        <slot />
      </main>

    </div>
  </div>
</template>

<script setup>
import Sidebar from '@/components/admin/layout/Sidebar.vue'
</script>

<style>
@import '@/assets/admin/css/app.css';
</style>

<style scoped>
/* === Layout Shell === */
.admin-layout {
  display: flex;
  min-height: 100vh;
  background-color: #090d16;
  color: #cbd5e1;
  font-family: var(--font-text, 'Montserrat', sans-serif);
}

/* === Main Area (Right of Sidebar) === */
.admin-main {
  flex: 1;
  display: flex;
  flex-direction: column;
  min-height: 100vh;
  min-width: 0;
}

/* === Topbar === */
.admin-topbar {
  height: 64px;
  background: rgba(9, 13, 22, 0.85);
  backdrop-filter: blur(12px);
  border-bottom: 1px solid #1e293b;
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 0 28px;
  position: sticky;
  top: 0;
  z-index: 10;
  flex-shrink: 0;
}

/* === Breadcrumb === */
.topbar-breadcrumb {
  display: flex;
  align-items: center;
  gap: 6px;
}

.breadcrumb-item {
  font-size: 12px;
  color: #475569;
  cursor: pointer;
  transition: color 0.15s;
}

.breadcrumb-item:hover {
  color: #94a3b8;
}

.breadcrumb-sep {
  width: 14px;
  height: 14px;
  color: #334155;
}

.breadcrumb-current {
  font-size: 12px;
  font-weight: 600;
  color: #e2e8f0;
}

/* === Topbar Actions === */
.topbar-actions {
  display: flex;
  align-items: center;
  gap: 16px;
}

/* Status Pill */
.status-pill {
  display: flex;
  align-items: center;
  gap: 6px;
  padding: 4px 12px;
  background: rgba(16, 185, 129, 0.08);
  border: 1px solid rgba(16, 185, 129, 0.18);
  border-radius: 100px;
  font-size: 10px;
  font-weight: 700;
  letter-spacing: 0.07em;
  text-transform: uppercase;
  color: #34d399;
}

.status-dot {
  width: 6px;
  height: 6px;
  border-radius: 50%;
  background: #34d399;
  animation: pulse 2s infinite;
}

@keyframes pulse {
  0%, 100% { opacity: 1; }
  50% { opacity: 0.4; }
}

/* Topbar Icon Button */
.topbar-btn {
  position: relative;
  background: transparent;
  border: none;
  padding: 8px;
  border-radius: 8px;
  color: #64748b;
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: center;
  transition: color 0.15s, background 0.15s;
}

.topbar-btn svg {
  width: 20px;
  height: 20px;
}

.topbar-btn:hover {
  color: #e2e8f0;
  background: rgba(255, 255, 255, 0.05);
}

/* Notification red dot */
.notif-dot {
  position: absolute;
  top: 7px;
  right: 7px;
  width: 8px;
  height: 8px;
  border-radius: 50%;
  background: #eab308;
  border: 2px solid #090d16;
}

/* Admin User */
.topbar-user {
  display: flex;
  align-items: center;
  gap: 10px;
  padding-left: 16px;
  border-left: 1px solid #1e293b;
}

.topbar-username {
  font-size: 12px;
  font-weight: 600;
  color: #cbd5e1;
}

.topbar-avatar {
  width: 32px;
  height: 32px;
  border-radius: 50%;
  background: #1e293b;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 11px;
  font-weight: 800;
  color: #eab308;
  letter-spacing: 0.02em;
  flex-shrink: 0;
}

/* === Main Content === */
.admin-content {
  flex: 1;
  padding: 28px;
  overflow-y: auto;
}

.admin-content::-webkit-scrollbar {
  width: 5px;
}

.admin-content::-webkit-scrollbar-track {
  background: #090d16;
}

.admin-content::-webkit-scrollbar-thumb {
  background: rgba(255, 255, 255, 0.08);
  border-radius: 3px;
}

.admin-content::-webkit-scrollbar-thumb:hover {
  background: #2563eb;
}
</style>
