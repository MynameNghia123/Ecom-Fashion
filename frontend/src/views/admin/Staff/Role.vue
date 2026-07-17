<template>
  <div class="space-y-6">

    <!-- ═══ PAGE HEADER ═══════════════════════════════════════════════════════ -->
    <div class="flex items-center justify-between flex-wrap gap-4">
      <div>
        <h1 class="text-2xl font-bold text-slate-800 tracking-tight">Vai trò & Quyền hạn</h1>
        <p class="text-sm text-slate-500 mt-0.5">Quản lý các nhóm quyền truy cập vào hệ thống.</p>
      </div>
      <button
        @click="openAdd"
        class="inline-flex items-center gap-2 px-5 py-2.5 bg-[#0258cb] hover:bg-[#004bb3] text-white text-sm font-semibold rounded-xl shadow-md shadow-blue-200 transition-all duration-200 active:scale-[0.98]"
      >
        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
          <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
        </svg>
        Thêm vai trò mới
      </button>
    </div>

    <!-- ═══ TABLE CARD ════════════════════════════════════════════════════════ -->
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">

      <!-- Toolbar -->
      <div class="flex flex-wrap items-center gap-3 p-5 border-b border-slate-100">
        <div class="relative flex items-center flex-1 min-w-[220px] max-w-sm">
          <span class="absolute left-3.5 text-slate-400">
            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
              <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
            </svg>
          </span>
          <input
            type="text"
            placeholder="Tìm kiếm vai trò..."
            class="w-full pl-10 pr-4 py-2.5 text-sm border border-slate-200 rounded-xl text-slate-700 placeholder-slate-400 bg-slate-50 focus:bg-white focus:border-[#0258cb] focus:ring-4 focus:ring-[#0258cb]/10 focus:outline-none transition-all duration-200"
          />
        </div>
        <button class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-medium text-slate-600 bg-white border border-slate-200 rounded-xl hover:bg-slate-50 transition-colors">
          <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"/>
          </svg>
          Lọc
        </button>
      </div>

      <!-- Table -->
      <div class="overflow-x-auto">
        <table class="w-full text-sm">
          <thead>
            <tr class="bg-slate-50/80 border-b border-slate-100">
              <th class="py-4 px-6 text-left text-[11px] font-bold text-slate-400 uppercase tracking-wider w-[80px]">ID</th>
              <th class="py-4 px-6 text-left text-[11px] font-bold text-slate-400 uppercase tracking-wider">Tên vai trò</th>
              <th class="py-4 px-6 text-left text-[11px] font-bold text-slate-400 uppercase tracking-wider">Mô tả</th>
              <th class="py-4 px-6 text-center text-[11px] font-bold text-slate-400 uppercase tracking-wider w-[100px]">Số NV</th>
              <th class="py-4 px-6 text-center text-[11px] font-bold text-slate-400 uppercase tracking-wider w-[140px]">Ngày tạo</th>
              <th class="py-4 px-6 text-center text-[11px] font-bold text-slate-400 uppercase tracking-wider w-[140px]">Thao tác</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-slate-50">
            <tr v-for="role in roles" :key="role.id" class="hover:bg-blue-50/30 transition-colors duration-100">
              <td class="py-4 px-6 font-mono text-xs text-slate-400">#{{ role.id }}</td>
              <td class="py-4 px-6">
                <div class="flex items-center gap-2.5">
                  <div
                    class="w-8 h-8 rounded-lg flex items-center justify-center text-white text-xs font-bold shrink-0"
                    :class="role.color"
                  >
                    {{ role.name.slice(0, 2).toUpperCase() }}
                  </div>
                  <span class="font-semibold text-slate-700">{{ role.name }}</span>
                </div>
              </td>
              <td class="py-4 px-6 text-slate-500 text-xs max-w-[200px] truncate">{{ role.description }}</td>
              <td class="py-4 px-6 text-center font-bold text-slate-700">{{ role.staffCount }}</td>
              <td class="py-4 px-6 text-center text-xs text-slate-500">{{ role.createdAt }}</td>
              <td class="py-4 px-6">
                <div class="flex items-center justify-center gap-1.5">
                  <button @click="openView(role)" class="p-1.5 text-blue-500 hover:bg-blue-50 rounded-lg transition-colors" title="Xem chi tiết">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                      <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>
                    </svg>
                  </button>
                  <button @click="openEdit(role)" class="p-1.5 text-amber-500 hover:bg-amber-50 rounded-lg transition-colors" title="Chỉnh sửa">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                      <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                      <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                    </svg>
                  </button>
                  <button @click="openDelete(role)" class="p-1.5 text-red-500 hover:bg-red-50 rounded-lg transition-colors" title="Xóa">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                      <polyline points="3 6 5 6 21 6"/>
                      <path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/>
                      <path d="M10 11v6M14 11v6M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"/>
                    </svg>
                  </button>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Pagination -->
      <div class="px-6 py-4 border-t border-slate-100 flex items-center justify-between">
        <p class="text-xs font-medium text-slate-500">Hiển thị 1 - 4 trong 4 vai trò</p>
        <div class="flex items-center gap-1">
          <button class="w-8 h-8 flex items-center justify-center rounded-lg border border-slate-200 text-slate-400 hover:border-[#0258cb] hover:text-[#0258cb] transition-all disabled:opacity-40" disabled>
            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="15 18 9 12 15 6"/></svg>
          </button>
          <button class="w-8 h-8 flex items-center justify-center rounded-lg bg-[#0258cb] text-white text-xs font-bold">1</button>
          <button class="w-8 h-8 flex items-center justify-center rounded-lg border border-slate-200 text-slate-400 hover:border-[#0258cb] hover:text-[#0258cb] transition-all disabled:opacity-40" disabled>
            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"/></svg>
          </button>
        </div>
      </div>
    </div>

    <!-- ═══ MODAL: ADD / EDIT ══════════════════════════════════════════════════ -->
    <div v-if="modal === 'form'" class="fixed inset-0 z-[9998] flex items-center justify-center p-4">
      <div class="absolute inset-0 bg-slate-900/50 backdrop-blur-[2px]" @click="closeModal"></div>
      <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-[760px] animate-modal-in flex flex-col max-h-[90vh]">

        <!-- Header -->
        <div class="flex items-center justify-between px-7 pt-6 pb-5 border-b border-slate-100 shrink-0">
          <div class="flex items-center gap-3">
            <div class="w-9 h-9 rounded-xl bg-blue-50 flex items-center justify-center">
              <svg v-if="isAdding" class="w-5 h-5 text-[#0258cb]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
              </svg>
              <svg v-else class="w-5 h-5 text-[#0258cb]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
            </div>
            <h2 class="text-base font-bold text-slate-800">{{ isAdding ? 'Thêm vai trò mới' : 'Chỉnh sửa vai trò' }}</h2>
          </div>
          <button @click="closeModal" class="p-1.5 rounded-lg text-slate-400 hover:bg-slate-100 transition-colors">
            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
              <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
            </svg>
          </button>
        </div>

        <!-- Body -->
        <div class="px-7 py-6 overflow-y-auto space-y-6 flex-1">

          <!-- Name input -->
          <div>
            <label class="block text-xs font-bold text-slate-700 mb-1.5">
              Tên vai trò <span class="text-red-500">*</span>
            </label>
            <input
              :value="isAdding ? '' : selectedRole?.name"
              type="text"
              placeholder="VD: Quản lý kho"
              class="w-full max-w-sm px-3.5 py-2.5 text-sm border border-slate-200 rounded-xl text-slate-800 placeholder-slate-400 bg-slate-50 focus:bg-white focus:border-[#0258cb] focus:ring-4 focus:ring-[#0258cb]/10 focus:outline-none transition-all"
            />
          </div>

          <!-- Description -->
          <div>
            <label class="block text-xs font-bold text-slate-700 mb-1.5">Mô tả</label>
            <textarea
              :value="isAdding ? '' : selectedRole?.description"
              rows="2"
              placeholder="Mô tả ngắn về vai trò này..."
              class="w-full px-3.5 py-2.5 text-sm border border-slate-200 rounded-xl text-slate-800 placeholder-slate-400 bg-slate-50 focus:bg-white focus:border-[#0258cb] focus:ring-4 focus:ring-[#0258cb]/10 focus:outline-none transition-all resize-none"
            ></textarea>
          </div>

          <div class="border-t border-slate-100"></div>

          <!-- Permission Matrix -->
          <div>
            <h3 class="text-sm font-bold text-slate-800 mb-1">Phân quyền Module</h3>
            <p class="text-xs text-slate-500 mb-4">Chọn các quyền truy cập tương ứng cho từng phân hệ.</p>

            <div class="border border-slate-200 rounded-xl overflow-hidden">
              <table class="w-full text-sm">
                <thead class="bg-slate-100 border-b border-slate-200">
                  <tr>
                    <th class="py-3 px-4 text-left text-[11px] font-bold text-slate-500 uppercase tracking-wider w-[40%]">Chức năng</th>
                    <th class="py-3 px-4 text-center text-[11px] font-bold text-slate-500 uppercase tracking-wider">Read</th>
                    <th class="py-3 px-4 text-center text-[11px] font-bold text-slate-500 uppercase tracking-wider">Create</th>
                    <th class="py-3 px-4 text-center text-[11px] font-bold text-slate-500 uppercase tracking-wider">Write</th>
                    <th class="py-3 px-4 text-center text-[11px] font-bold text-slate-500 uppercase tracking-wider">Delete</th>
                  </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                  <template v-for="group in permissionMatrix" :key="group.groupName">
                    <!-- Group header row -->
                    <tr class="bg-slate-50/80">
                      <td colspan="5" class="py-2 px-4 text-[11px] font-bold text-slate-400 uppercase tracking-wider">
                        {{ group.groupName }}
                      </td>
                    </tr>
                    <!-- Module rows -->
                    <tr v-for="mod in group.modules" :key="mod" class="hover:bg-blue-50/20 transition-colors">
                      <td class="py-3 px-4 text-slate-700 font-medium text-xs border-r border-slate-50">{{ mod }}</td>
                      <td class="py-3 px-4 text-center"><input type="checkbox" class="w-4 h-4 rounded text-[#0258cb] cursor-pointer" /></td>
                      <td class="py-3 px-4 text-center"><input type="checkbox" class="w-4 h-4 rounded text-[#0258cb] cursor-pointer" /></td>
                      <td class="py-3 px-4 text-center"><input type="checkbox" class="w-4 h-4 rounded text-[#0258cb] cursor-pointer" /></td>
                      <td class="py-3 px-4 text-center"><input type="checkbox" class="w-4 h-4 rounded text-[#0258cb] cursor-pointer" /></td>
                    </tr>
                  </template>
                </tbody>
              </table>
            </div>
          </div>
        </div>

        <!-- Footer -->
        <div class="flex items-center justify-end gap-3 px-7 py-5 border-t border-slate-100 shrink-0">
          <button @click="closeModal" class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-600 font-semibold text-sm hover:bg-slate-50 transition-all">Hủy bỏ</button>
          <button class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-[#0258cb] hover:bg-[#004bb3] text-white font-semibold text-sm transition-all active:scale-[0.98]">
            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/>
              <polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/>
            </svg>
            {{ isAdding ? 'Thêm vai trò' : 'Lưu thay đổi' }}
          </button>
        </div>
      </div>
    </div>

    <!-- ═══ MODAL: VIEW DETAIL ════════════════════════════════════════════════ -->
    <div v-if="modal === 'view'" class="fixed inset-0 z-[9998] flex items-center justify-center p-4">
      <div class="absolute inset-0 bg-slate-900/50 backdrop-blur-[2px]" @click="closeModal"></div>
      <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-[480px] animate-modal-in flex flex-col max-h-[90vh]">

        <!-- Header -->
        <div class="flex items-center justify-between px-7 pt-6 pb-5 border-b border-slate-100">
          <div class="flex items-center gap-3">
            <div class="w-9 h-9 rounded-xl bg-blue-50 flex items-center justify-center">
              <svg class="w-5 h-5 text-[#0258cb]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>
              </svg>
            </div>
            <h2 class="text-base font-bold text-slate-800">Chi tiết vai trò</h2>
          </div>
          <button @click="closeModal" class="p-1.5 rounded-lg text-slate-400 hover:bg-slate-100 transition-colors">
            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
          </button>
        </div>

        <!-- Body -->
        <div class="px-7 py-6 space-y-4 overflow-y-auto">
          <div class="flex items-center gap-4 p-4 bg-slate-50 rounded-2xl">
            <div class="w-14 h-14 rounded-2xl flex items-center justify-center text-white text-lg font-bold shrink-0" :class="selectedRole?.color">
              {{ selectedRole?.name?.slice(0, 2).toUpperCase() }}
            </div>
            <div>
              <p class="text-base font-bold text-slate-800">{{ selectedRole?.name }}</p>
              <p class="text-xs text-slate-500 mt-0.5">{{ selectedRole?.description }}</p>
            </div>
          </div>

          <div class="grid grid-cols-2 gap-3">
            <div class="bg-slate-50 rounded-xl px-4 py-3">
              <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">ID</p>
              <p class="text-sm font-mono font-semibold text-slate-700">#{{ selectedRole?.id }}</p>
            </div>
            <div class="bg-slate-50 rounded-xl px-4 py-3">
              <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Số nhân viên</p>
              <p class="text-sm font-bold text-slate-700">{{ selectedRole?.staffCount }} người</p>
            </div>
            <div class="bg-slate-50 rounded-xl px-4 py-3 col-span-2">
              <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Ngày tạo</p>
              <p class="text-sm text-slate-700">{{ selectedRole?.createdAt }}</p>
            </div>
          </div>
        </div>

        <div class="flex items-center justify-end gap-3 px-7 py-5 border-t border-slate-100">
          <button @click="closeModal" class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-600 font-semibold text-sm hover:bg-slate-50 transition-all">Đóng</button>
          <button @click="openEdit(selectedRole)" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-amber-50 border border-amber-200 text-amber-600 font-semibold text-sm hover:bg-amber-100 transition-all">
            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
            Chỉnh sửa
          </button>
        </div>
      </div>
    </div>

    <!-- ═══ MODAL: CONFIRM DELETE ════════════════════════════════════════════ -->
    <div v-if="modal === 'delete'" class="fixed inset-0 z-[9998] flex items-center justify-center p-4">
      <div class="absolute inset-0 bg-slate-900/50 backdrop-blur-[2px]" @click="closeModal"></div>
      <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-[420px] animate-modal-in">
        <div class="p-7 text-center">
          <div class="w-14 h-14 rounded-2xl bg-red-50 flex items-center justify-center mx-auto mb-4">
            <svg class="w-7 h-7 text-red-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/>
              <path d="M10 11v6M14 11v6M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"/>
            </svg>
          </div>
          <h3 class="text-base font-bold text-slate-800 mb-2">Xóa vai trò?</h3>
          <p class="text-sm text-slate-500 mb-1">Bạn có chắc chắn muốn xóa vai trò</p>
          <p class="text-sm font-bold text-slate-800 mb-1">"{{ selectedRole?.name }}"?</p>
          <p class="text-xs text-slate-400">Hành động này có thể ảnh hưởng đến các nhân viên đang có vai trò này.</p>
        </div>
        <div class="flex items-center gap-3 px-7 pb-7">
          <button @click="closeModal" class="flex-1 py-2.5 rounded-xl border border-slate-200 text-slate-600 font-semibold text-sm hover:bg-slate-50 transition-all">Hủy bỏ</button>
          <button class="flex-1 py-2.5 rounded-xl bg-red-500 hover:bg-red-600 text-white font-semibold text-sm transition-all active:scale-[0.98]">Xóa vai trò</button>
        </div>
      </div>
    </div>

  </div>
</template>

<script setup>
import { ref } from 'vue'

// ─── Static data (chỉ để xem giao diện) ─────────────────────────────────────
const roles = ref([
  { id: 1, name: 'Super Administrator', description: 'Quyền quản trị toàn hệ thống, không giới hạn.', staffCount: 2, createdAt: '16/07/2026', color: 'bg-violet-500' },
  { id: 2, name: 'Manager', description: 'Quản lý các hoạt động bán hàng và kho hàng.', staffCount: 5, createdAt: '16/07/2026', color: 'bg-blue-500' },
  { id: 3, name: 'Content Editor', description: 'Chỉnh sửa nội dung sản phẩm và blog.', staffCount: 12, createdAt: '15/07/2026', color: 'bg-emerald-500' },
  { id: 4, name: 'Support Staff', description: 'Xử lý đơn hàng và chăm sóc khách hàng.', staffCount: 8, createdAt: '14/07/2026', color: 'bg-amber-500' },
])

const permissionMatrix = [
  { groupName: 'Quản lý sản phẩm', modules: ['Danh mục', 'Sản phẩm & Biến thể', 'Thuộc tính sản phẩm'] },
  { groupName: 'Quản lý bán hàng', modules: ['Đơn hàng', 'Yêu cầu đổi trả'] },
  { groupName: 'Quản lý kho', modules: ['Nhà cung cấp', 'Phiếu nhập kho'] },
  { groupName: 'Khách hàng & Đánh giá', modules: ['Quản lý khách hàng', 'Quản lý Đánh giá'] },
  { groupName: 'Tiếp thị', modules: ['Mã giảm giá', 'Banner quảng cáo'] },
  { groupName: 'Quản lý nội dung', modules: ['Quản lý Blog'] },
  { groupName: 'Phân quyền & Nhân sự', modules: ['Tài khoản nhân viên', 'Vai trò & Quyền hạn'] },
]

// ─── Modal state ──────────────────────────────────────────────────────────────
const modal = ref(null)         // null | 'form' | 'view' | 'delete'
const isAdding = ref(true)
const selectedRole = ref(null)

const openAdd    = ()     => { isAdding.value = true;  selectedRole.value = null; modal.value = 'form' }
const openEdit   = (role) => { isAdding.value = false; selectedRole.value = role; modal.value = 'form' }
const openView   = (role) => { selectedRole.value = role; modal.value = 'view' }
const openDelete = (role) => { selectedRole.value = role; modal.value = 'delete' }
const closeModal = ()     => { modal.value = null }
</script>

<style scoped>
@keyframes modalIn {
  from { opacity: 0; transform: scale(0.95) translateY(8px); }
  to   { opacity: 1; transform: scale(1) translateY(0); }
}
.animate-modal-in {
  animation: modalIn 0.2s cubic-bezier(0.34, 1.4, 0.64, 1) forwards;
}
</style>
