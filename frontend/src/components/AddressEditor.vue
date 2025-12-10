<template>
  <div>
    <h3>Endereços</h3>

    <div v-for="(addr, index) in addresses" :key="index" class="address-block">
      <input v-model="addr.street" placeholder="Rua" />
      <input v-model="addr.number" placeholder="Número" />
      <input v-model="addr.city" placeholder="Cidade" />
      <input v-model="addr.state" placeholder="UF" />
      <input v-model="addr.zip" placeholder="CEP" />

      <button @click="remove(index)">Remover</button>
    </div>

    <button @click="add">Adicionar Endereço</button>
  </div>
</template>

<script setup>
const props = defineProps({
  modelValue: { type: Array, required: true }
});

const emit = defineEmits(["update:modelValue"]);

function add() {
  const arr = [...props.modelValue];
  arr.push({ street: "", number: "", city: "", state: "", zip: "" });
  emit("update:modelValue", arr);
}

function remove(index) {
  const arr = [...props.modelValue];
  arr.splice(index, 1);
  emit("update:modelValue", arr);
}

const addresses = props.modelValue;
</script>

<style>
.address-block {
  margin-bottom: 10px;
  display: flex;
  gap: 10px;
}
</style>
