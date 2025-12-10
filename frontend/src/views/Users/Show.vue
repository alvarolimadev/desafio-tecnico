<template>
  <div class="container">

    <button class="btn-primary" @click="$router.push('/')">Voltar</button>

    <h2 class="title">Detalhes do Usuário</h2>

    <!-- CARD DE DADOS DO USUÁRIO -->
    <div class="card">
      <h3 class="section-title">Informações Pessoais</h3>

      <div class="info-grid">
        <div>
          <label>Nome</label>
          <p>{{ user.name }}</p>
        </div>

        <div>
          <label>CPF</label>
          <p>{{ maskCPF(user.cpf) }}</p>
        </div>

        <div>
          <label>Email</label>
          <p>{{ user.email }}</p>
        </div>

        <div>
          <label>Perfil</label>
          <p>{{ user.profile?.name }}</p>
        </div>
      </div>
    </div>


    <!-- ENDEREÇOS -->
    <div class="card">
      <h3 class="section-title">Endereços</h3>

      <div 
        v-for="addr in user.addresses" 
        :key="addr.id" 
        class="address-card"
      >
        <div class="addr-grid">
          <div>
            <label>Rua</label>
            <p>{{ addr.street }}</p>
          </div>

          <div>
            <label>Número</label>
            <p>{{ addr.number || '-' }}</p>
          </div>

          <div>
            <label>Complemento</label>
            <p>{{ addr.complement || '-' }}</p>
          </div>

          <div>
            <label>Bairro</label>
            <p>{{ addr.neighborhood || '-' }}</p>
          </div>

          <div>
            <label>Cidade</label>
            <p>{{ addr.city }}</p>
          </div>

          <div>
            <label>Estado</label>
            <p>{{ addr.state }}</p>
          </div>

          <div>
            <label>CEP</label>
            <p>{{ maskCEP(addr.zip) }}</p>
          </div>

          <div>
            <label>País</label>
            <p>{{ addr.country }}</p>
          </div>
        </div>
      </div>
    </div>

  </div>
</template>

<script setup>
import { ref, onMounted } from "vue";
import { useRoute } from "vue-router";
import api from "@/services/api";
import { maskCPF, maskCEP } from "@/assets/index.js";

const route = useRoute();
const user = ref({});

onMounted(async () => {
  const response = await api.get(`/users/${route.params.id}`);
  user.value = response.data.data;
});
</script>

<style scoped>
.container {
  max-width: 900px;
  margin: auto;
  padding: 20px;
  font-family: Arial, sans-serif;
}

.btn-primary {
  margin-bottom: 20px;
  padding: 6px 12px;
  background: #0069d9;
  color: white;
  border: none;
  border-radius: 6px;
  cursor: pointer;
}

.title {
  margin-bottom: 20px;
}

.card {
  background: #fff;
  border: 1px solid #ddd;
  padding: 20px;
  border-radius: 8px;
  margin-bottom: 30px;
}

.section-title {
  font-weight: bold;
  margin-bottom: 15px;
}

.info-grid {
  display: grid;
  grid-template-columns: repeat(2, 1fr);
  gap: 15px;
}

label {
  font-weight: bold;
  display: block;
  margin-bottom: 4px;
}

/* CARD DE ENDEREÇO */
.address-card {
  padding: 15px;
  border: 1px solid #e4e4e4;
  border-radius: 8px;
  background: #fafafa;
  margin-bottom: 20px;
}

.addr-grid {
  display: grid;
  grid-template-columns: repeat(2, 1fr);
  gap: 12px;
}

p {
  margin: 0;
}
</style>