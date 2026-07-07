    <Teleport to="body">
      <Transition name="modal-fade">
        <div
          v-if="showEditModal"
          class="fixed inset-0 z-[9998] flex items-center justify-center p-4"
          @click.self="showEditModal = false"
        >
          <div class="absolute inset-0 bg-slate-900/50 backdrop-blur-[2px]"></div>
          <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-[700px] animate-modal-in flex flex-col max-h-[90vh]">

            <!-- Header -->
            <div class="flex items-center justify-between px-7 pt-6 pb-4 border-b border-slate-100">
              <div>
                <h2 class="text-base font-bold text-slate-800">Chỉnh sửa phiếu nhập kho #{{ editForm.code }}</h2>
                <p class="text-xs text-slate-400 mt-0.5">Đang cập nhật phiếu nhập lúc {{ currentDateTime }}</p>
              </div>
              <button @click="showEditModal = false" class="p-1.5 rounded-lg text-slate-400 hover:text-slate-600 hover:bg-slate-100 transition-colors">
                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                  <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
                </svg>
              </button>
            </div>

            <!-- Body -->
            <div class="px-7 py-6 overflow-y-auto space-y-5">

              <!-- NCC + Status -->
              <div class="grid grid-cols-2 gap-4">
                <div>
                  <label class="block text-xs font-bold text-slate-700 mb-1.5">Nhà cung cấp</label>
                  <div class="relative">
                    <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400">
                      <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 7V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v2"/>
                      </svg>
                    </span>
                    <select
                      v-model="editForm.supplier_id"
                      class="w-full pl-10 pr-4 py-2.5 text-sm border border-slate-200 rounded-xl text-slate-700 bg-slate-50 focus:bg-white focus:border-[#0258cb] focus:ring-4 focus:ring-[#0258cb]/10 focus:outline-none transition-all duration-200"
                    >
                      <option v-for="s in mockSuppliers" :key="s.id" :value="s.id">{{ s.name }}</option>
                    </select>
                  </div>
                </div>
                <div>
                  <label class="block text-xs font-bold text-slate-700 mb-1.5">Trạng thái phiếu</label>
                  <select
                    v-model="editForm.status"
                    class="w-full px-3.5 py-2.5 text-sm border border-slate-200 rounded-xl text-slate-700 bg-slate-50 focus:bg-white focus:border-[#0258cb] focus:ring-4 focus:ring-[#0258cb]/10 focus:outline-none transition-all duration-200"
                  >
                    <option value="pending">Đang chờ duyệt</option>
                    <option value="approved">Đã duyệt</option>
                    <option value="completed">Đã hoàn thành</option>
                    <option value="cancelled">Đã huỷ</option>
                  </select>
                </div>
              </div>

              <!-- Product list (edit) -->
              <div>
                <div class="flex items-center justify-between mb-3">
                  <p class="text-sm font-bold text-slate-700">Danh sách sản phẩm</p>
                  <button @click="addProductRow(editForm)" class="inline-flex items-center gap-1 text-sm font-semibold text-[#0258cb] hover:text-[#004bb3] transition-colors">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                      <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
                    </svg>
                    Thêm sản phẩm
                  </button>
                </div>

                <div class="rounded-xl border border-slate-200 overflow-hidden">
                  <table class="w-full text-sm">
                    <thead>
                      <tr class="bg-slate-50 border-b border-slate-100">
                        <th class="py-2.5 px-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Sản phẩm / Biến thể</th>
                        <th class="py-2.5 px-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider w-24">Số lượng</th>
                        <th class="py-2.5 px-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider w-32">Đơn giá nhập</th>
                        <th class="w-10"></th>
                      </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                      <tr v-for="(item, idx) in editForm.items" :key="idx">
                        <td class="py-2.5 px-4">
                          <p class="font-semibold text-slate-800 text-sm">{{ item.product_name }}</p>
                          <p class="text-xs text-slate-400 mt-0.5">{{ item.variant }}</p>
                        </td>
                        <td class="py-2 px-4">
                          <input
                            v-model.number="item.qty"
                            type="number"
                            min="1"
                            class="w-full px-3 py-2 text-sm border border-slate-200 rounded-lg bg-slate-50 focus:bg-white focus:border-[#0258cb] focus:ring-4 focus:ring-[#0258cb]/10 focus:outline-none transition-all text-center"
                          />
                        </td>
                        <td class="py-2 px-4">
                          <div class="flex items-center gap-1">
                            <input
                              v-model.number="item.import_price"
                              type="number"
                              min="0"
                              class="w-full px-3 py-2 text-sm border border-slate-200 rounded-lg bg-slate-50 focus:bg-white focus:border-[#0258cb] focus:ring-4 focus:ring-[#0258cb]/10 focus:outline-none transition-all"
                            />
                            <span class="text-slate-400 text-xs font-medium shrink-0">đ</span>
                          </div>
                        </td>
                        <td class="py-2 px-2">
                          <button @click="removeProductRow(editForm, idx)" class="p-1.5 rounded-lg text-slate-300 hover:text-red-500 hover:bg-red-50 transition-all duration-150">
                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                              <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
                            </svg>
                          </button>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>

                <!-- Totals -->
                <div class="mt-4 space-y-2 text-sm text-slate-600">
                  <div class="flex justify-between">
                    <span>Tổng số lượng:</span>
                    <span class="font-semibold text-slate-800">{{ editForm.items.reduce((s,i) => s + (i.qty||0), 0) }}</span>
                  </div>
                  <div class="flex justify-between">
                    <span>Tổng tiền hàng:</span>
                    <span class="font-semibold text-slate-800">{{ formatPrice(editSubtotal) }}</span>
                  </div>
                  <div class="flex justify-between items-center">
                    <span>Chi phí khác (Vận chuyển, thuế...):</span>
                    <input
                      v-model.number="editForm.extra_cost"
                      type="number"
                      min="0"
                      class="w-28 px-2 py-1 text-sm border border-slate-200 rounded-lg bg-slate-50 focus:border-[#0258cb] focus:outline-none text-right"
                    />
                    <span class="text-slate-400 ml-1">đ</span>
                  </div>
                  <div class="pt-2 border-t border-slate-200 flex justify-between items-center">
                    <span class="font-bold text-slate-700">Tổng cộng:</span>
                    <span class="text-xl font-bold text-[#0258cb]">{{ formatPrice(editSubtotal + (editForm.extra_cost || 0)) }}</span>
                  </div>
                </div>
              </div>
            </div>

            <!-- Footer -->
            <div class="flex items-center justify-end gap-3 px-7 py-5 border-t border-slate-100">
              <button @click="showEditModal = false" class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-600 font-semibold text-sm hover:bg-slate-50 transition-all duration-150">Hủy</button>
              <button
                @click="submitEdit"
                :disabled="editSubmitting"
                class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-[#0258cb] hover:bg-[#004bb3] text-white font-semibold text-sm transition-all duration-150 disabled:opacity-60 active:scale-[0.98]"
              >
                <svg v-if="editSubmitting" class="w-4 h-4 animate-spin" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12a9 9 0 1 1-6.219-8.56"/></svg>
                <svg v-else class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/>
                  <polyline points="17 21 17 13 7 13 7 21"/>
                </svg>
                {{ editSubmitting ? 'Đang lưu...' : 'Cập nhật phiếu nhập' }}
              </button>
            </div>
          </div>
        </div>
      </Transition>
    </Teleport>