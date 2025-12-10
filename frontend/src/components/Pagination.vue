<template>
  <div v-if="meta && meta.last_page > 1" class="pagination">
    <button @click="change(meta.current_page - 1)" :disabled="meta.current_page === 1">
      Anterior
    </button>

    <span>Página {{ meta.current_page }} de {{ meta.last_page }}</span>

    <button @click="change(meta.current_page + 1)" :disabled="meta.current_page === meta.last_page">
      Próxima
    </button>
  </div>
</template>

<script setup>
const props = defineProps({
  meta: { type: Object, required: true }
});

const emit = defineEmits(["change"]);

function change(page) {
  if (page >= 1 && page <= props.meta.last_page) {
    emit("change", page);
  }
}
</script>

<style>
.pagination {
  display: flex;
  gap: 12px;
  align-items: center;
  margin-top: 20px;
}
</style>
