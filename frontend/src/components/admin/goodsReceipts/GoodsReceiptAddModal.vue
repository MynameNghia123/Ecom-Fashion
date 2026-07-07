    <Teleport to="body">
      <Transition name="modal-fade">
        <div
          v-if="showAddModal"
          class="fixed inset-0 z-[9998] flex items-center justify-center p-4"
          @click.self="showAddModal = false"
        >
          <div class="absolute inset-0 bg-slate-900/50 backdrop-blur-[2px]"></div>
          <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-[700px] animate-modal-in flex flex-col max-h-[90vh]">

            <!-- Header -->
            <div class="flex items-center justify-between px-7 pt-6 pb-5 border-b border-slate-100">
              <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-blue-50 flex items-center justify-center">
                  <svg class="w-5 h-5 text-[#0258cb]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
                  </svg>
                </div>
                <h2 class="text-base font-bold text-slate-800">Thêm phiếu nhập kho mới</h2>
              </div>
              <button @click="showAddModal = false" class="p-1.5 rounded-lg text-slate-400 hover:text-slate-600 hover:bg-slate-100 transition-colors">
                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                  <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
                </svg>
              </button>
            </div>

            <!-- Body -->
            <div class="px-7 py-6 overflow-y-auto space-y-6">

              <!-- Mã phiếu + NCC -->
              <div class="grid grid-cols-2 gap-4">
                <div>
                  <label class="block text-xs font-bold text-slate-700 mb-1.5">Mã phiếu nhập</label>
                  <input
                    type="text"
                    :value="autoReceiptCode"
                    readonly
                    placeholder="Mã tự động sinh"
                    class="w-full px-3.5 py-2.5 text-sm border border-slate-200 rounded-xl text-slate-400 bg-slate-50 focus:outline-none cursor-not-allowed font-mono"
                  />
                </div>
                <div>
                  <label class="block text-xs font-bold text-slate-700 mb-1.5">
                    Nhà cung cấp <span class="text-red-500">*</span>
                  </label>
                  <select
                    v-model="addForm.supplier_id"
                    class="w-full px-3.5 py-2.5 text-sm border border-slate-200 rounded-xl text-slate-700 bg-slate-50 focus:bg-white focus:border-[#0258cb] focus:ring-4 focus:ring-[#0258cb]/10 focus:outline-none transition-all duration-200"
                    :class="addFormErrors.supplier_id ? 'border-red-400' : ''"
                  >
                    <option value="">Chọn nhà cung cấp</option>
                    <option v-for="s in mockSuppliers" :key="s.id" :value="s.id">{{ s.name }}</option>
                  </select>
                  <p v-if="addFormErrors.supplier_id" class="text-xs text-red-500 mt-1">{{ addFormErrors.supplier_id }}</p>
                </div>
              </div>

              <!-- Người giao hàng + Chi phí khác -->


              <!-- Product list -->
              <div>
                <div class="flex items-center justify-between mb-3">
                  <p class="text-sm font-bold text-slate-700">Danh sách sản phẩm</p>
                  <button
                    @click="addProductRow(addForm)"
                    class="inline-flex items-center gap-1 text-sm font-semibold text-[#0258cb] hover:text-[#004bb3] transition-colors"
                  >
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                      <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
                    </svg>
                    Thêm dòng
                  </button>
                </div>

                <div class="rounded-xl border border-slate-200 overflow-hidden">
                  <table class="w-full text-sm">
                    <thead>
                      <tr class="bg-slate-50 border-b border-slate-100">
                        <th class="py-2.5 px-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider w-8">STT</th>
                        <th class="py-2.5 px-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Sản phẩm (Biến thể)</th>
                        <th class="py-2.5 px-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider w-24">Số lượng</th>
                        <th class="py-2.5 px-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider w-32">Đơn giá nhập</th>
                        <th class="py-2.5 px-4 text-right text-xs font-bold text-slate-500 uppercase tracking-wider w-32">Thành tiền</th>
                        <th class="w-10"></th>
                      </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                      <tr v-for="(item, idx) in addForm.items" :key="idx">
                        <td class="py-3 px-4 text-slate-500 font-mono text-xs">{{ idx + 1 }}</td>
                        <td class="py-2 px-4">
                          <!-- Product search with dropdown -->
                          <div class="relative">
                            <input
                              v-model="item.product_name"
                              @input="onProductSearch(item, $event.target.value)"
                              @focus="item.showDropdown = true"
                              @blur="hideDropdown(item)"
                              type="text"
                              placeholder="Tìm sản phẩm / biến thể..."
                              class="w-full px-3 py-2 text-sm border border-slate-200 rounded-lg text-slate-800 placeholder-slate-400 bg-slate-50 focus:bg-white focus:border-[#0258cb] focus:ring-4 focus:ring-[#0258cb]/10 focus:outline-none transition-all duration-200"
                            />
                            <!-- Dropdown -->
                            <div
                              v-if="item.showDropdown && item.searchResults?.length"
                              class="absolute top-full left-0 right-0 mt-1 bg-white rounded-xl border border-slate-200 shadow-lg z-50 overflow-hidden"
                            >
                              <button
                                v-for="prod in item.searchResults"
                                :key="prod.id"
                                @mousedown.prevent="selectProduct(item, prod)"
                                class="w-full text-left px-4 py-2.5 hover:bg-blue-50 transition-colors duration-100 border-b border-slate-50 last:border-0"
                              >
                                <p class="text-sm font-semibold text-slate-800">{{ prod.name }}</p>
                                <p class="text-xs text-slate-400 mt-0.5">SKU: {{ prod.sku }}</p>
                              </button>
                            </div>
                          </div>
                        </td>
                        <td class="py-2 px-4">
                          <input
                            v-model.number="item.qty"
                            type="number"
                            min="1"
                            class="w-full px-3 py-2 text-sm border border-slate-200 rounded-lg text-slate-800 bg-slate-50 focus:bg-white focus:border-[#0258cb] focus:ring-4 focus:ring-[#0258cb]/10 focus:outline-none transition-all duration-200 text-center"
                          />
                        </td>
                        <td class="py-2 px-4">
                          <input
                            v-model.number="item.import_price"
                            type="number"
                            min="0"
                            class="w-full px-3 py-2 text-sm border border-slate-200 rounded-lg text-slate-800 bg-slate-50 focus:bg-white focus:border-[#0258cb] focus:ring-4 focus:ring-[#0258cb]/10 focus:outline-none transition-all duration-200"
                          />
                        </td>
                        <td class="py-2 px-4 text-right font-semibold text-slate-700">
                          {{ formatPrice(item.qty * item.import_price) }}
                        </td>
                        <td class="py-2 px-2">
                          <button
                            @click="removeProductRow(addForm, idx)"
                            class="p-1.5 rounded-lg text-slate-300 hover:text-red-500 hover:bg-red-50 transition-all duration-150"
                          >
                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                              <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
                            </svg>
                          </button>
                        </td>
                      </tr>
                      <tr v-if="addForm.items.length === 0">
                        <td colspan="6" class="py-8 text-center text-sm text-slate-400">Chưa có sản phẩm nào. Nhấn "+ Thêm dòng" để bắt đầu.</td>
                      </tr>
                    </tbody>
                  </table>
                </div>

                <!-- Totals -->
                <div class="mt-4 flex justify-end">
                  <div class="w-64 space-y-2">
                    <div class="pt-2 border-t border-slate-200 flex justify-between items-center">
                      <span class="text-sm font-bold text-slate-700">Tổng cộng:</span>
                      <span class="text-lg font-bold text-[#0258cb]">{{ formatPrice(addSubtotal + (addForm.extra_cost || 0)) }}</span>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Footer -->
            <div class="flex items-center justify-end gap-3 px-7 py-5 border-t border-slate-100">
              <button @click="showAddModal = false" class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-600 font-semibold text-sm hover:bg-slate-50 transition-all duration-150">Hủy</button>
              <button
                @click="submitAdd"
                :disabled="addSubmitting"
                class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-[#0258cb] hover:bg-[#004bb3] text-white font-semibold text-sm transition-all duration-150 disabled:opacity-60 active:scale-[0.98]"
              >
                <svg v-if="addSubmitting" class="w-4 h-4 animate-spin" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12a9 9 0 1 1-6.219-8.56"/></svg>
                <svg v-else class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/>
                  <polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/>
                </svg>
                {{ addSubmitting ? 'Đang lưu...' : 'Lưu phiếu nhập' }}
              </button>
            </div>
          </div>
        </div>
      </Transition>
    </Teleport>