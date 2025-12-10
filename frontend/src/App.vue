<script setup>
import api from "./services/api.js";

async function testar() {
  try {
    const res = await api.get("/users");
    console.log("RESPOSTA DA API:", res.data);
  } catch (err) {
    console.error("ERRO AO CHAMAR API:", err);
  }
}

testar();
</script>

<template>
  <router-view />
</template>

