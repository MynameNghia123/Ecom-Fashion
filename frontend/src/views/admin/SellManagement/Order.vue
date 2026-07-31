<template>
  <div class="space-y-6">

    <!-- ═══════════════════════════════════════════════════════════
         PAGE HEADER
    ════════════════════════════════════════════════════════════════ -->
    <PageHeader
    @openCreateModal="isOpenCreateModal = true"
    />

    <!-- ═══════════════════════════════════════════════════════════
         STATS CARDS
    ════════════════════════════════════════════════════════════════ -->
    <StatsCard />

    <!-- ═══════════════════════════════════════════════════════════
         TABLE CARD
    ════════════════════════════════════════════════════════════════ -->
    <TableCard 
      :orders="orderStore.orders"
      :meta="orderStore.meta"
      :loading="orderStore.loading"
      @set-selected-order="setSelectedOrder"
      @set-action="action"
      @change-page="page => orderStore.fetchOrders({ page })"
      @change-per-page="per_page => { orderStore.meta.per_page = per_page; orderStore.fetchOrders({ page: 1, per_page }) }"
    />

  </div>
  <OrderCreateModal
  :isOpenCreateModal="isOpenCreateModal"
  @openCreateModal="isOpenCreateModal = true"
  @closeCreateModal="isOpenCreateModal = false"
  @saveCreateModal="saveCreateModal"
  />

  <OrderDetailModal
    :is-open-detail-modal="isOpenDetailModal"
    :selected-order="selectedOrder"
    @close-detail-modal="isOpenDetailModal = false"
  />

  <OrderUpdateModal
    :is-open-update-modal="isOpenUpdateModal"
    :selected-order="selectedOrder"
    @close-update-modal="isOpenUpdateModal = false"
  />

</template>
<script setup>
import { onMounted, ref } from 'vue';
import OrderCreateModal from '@/components/admin/order/OrderCreateModal.vue';
import OrderUpdateModal from '@/components/admin/order/OrderUpdateModal.vue';
import OrderDetailModal from '@/components/admin/order/OrderDetailModal.vue';
import PageHeader from '@/components/admin/order/PageHeader.vue';
import StatsCard from '@/components/admin/order/StatsCard.vue';
import TableCard from '@/components/admin/order/TableCard.vue';
import { useOrderStore } from '@/stores/admin/orderStore';

const orderStore = useOrderStore();
onMounted(() => {
  orderStore.fetchOrders();
})

const action = function(act)
{
  if (act === 'view'){
    isOpenDetailModal.value = true;
  }else if (act === 'edit'){
    isOpenUpdateModal.value = true;
  }else if (act === 'print'){
    // Xử lý in hóa đơn tại đây
    console.log("In hóa đơn cho đơn hàng:", selectedOrder.value);
  }else{
    console.error("dont choose action for this develop")
  }
}

const isOpenCreateModal = ref(false);
const saveCreateModal = function(){
  isOpenCreateModal.value = false;
}

const selectedOrder = ref(null);
const setSelectedOrder = function(order){
  selectedOrder.value = order;
}
const isOpenUpdateModal = ref(false);
const isOpenDetailModal = ref(false);



</script>
<style scoped>
@keyframes modalIn {
  from { opacity: 0; transform: scale(0.96) translateY(8px); }
  to   { opacity: 1; transform: scale(1) translateY(0); }
}
.animate-modal-in {
  animation: modalIn 0.22s cubic-bezier(0.34, 1.4, 0.64, 1) forwards;
}
</style>
