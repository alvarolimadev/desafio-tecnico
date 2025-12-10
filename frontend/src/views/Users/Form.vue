<template>
  <div class="container">

    <button class="btn-primary" @click="$router.push('/')">Voltar</button>

    <h2 class="title">
      {{ isEdit ? "Editar Usuário" : "Novo Usuário" }}
    </h2>

    <form class="form-card" @submit.prevent="submit">

      <!-- DADOS DO USUÁRIO -->
      <div class="section">
        <h3 class="section-title">Dados do Usuário</h3>

        <div class="grid-3">
          <div>
            <label>Nome</label>
            <input v-model="form.name" placeholder="Nome completo" required />
          </div>

          <div>
            <label>Email</label>
            <input v-model="form.email" type="email" placeholder="email@exemplo.com" required />
          </div>

          <div>
            <label>CPF</label>
            <input
              :value="maskCPF(form.cpf)"
              @input="form.cpf = maskCPF($event.target.value)"
              maxlength="14"
              placeholder="000.000.000-00"
              required
            />
          </div>
        </div>

        <div class="grid-1">
          <div>
            <label>Perfil</label>
            <select v-model="form.profile_id" required>
              <option disabled value="">Selecione um perfil...</option>
              <option v-for="p in profiles" :key="p.id" :value="p.id">
                {{ p.name }}
              </option>
            </select>
          </div>
        </div>

      </div>

      <!-- ENDEREÇOS -->
      <div class="section">
        <div class="address-header">
          <h3 class="section-title">Endereços</h3>
          <button class="btn-secondary" @click.prevent="addAddress">
            + Adicionar Endereço
          </button>
        </div>

        <div
          class="address-card"
          v-for="(addr, idx) in form.addresses"
          :key="idx"
        >
          <div class="grid-2">
            <div>
              <label>Rua</label>
              <input v-model="addr.street" placeholder="Rua" />
            </div>

            <div>
              <label>Número</label>
              <input v-model="addr.number" placeholder="Número" />
            </div>
          </div>

          <div class="grid-2">
            <div>
              <label>Complemento</label>
              <input v-model="addr.complement" placeholder="Apartamento, bloco, etc." />
            </div>

            <div>
              <label>Bairro</label>
              <input v-model="addr.neighborhood" placeholder="Bairro" />
            </div>
          </div>

          <div class="grid-3">
            <div>
              <label>Cidade</label>
              <input v-model="addr.city" placeholder="Cidade" />
            </div>

            <div>
              <label>Estado (UF)</label>
              <input v-model="addr.state" maxlength="2" placeholder="UF" />
            </div>

            <div>
              <label>CEP</label>
              <input 
                :value="maskCEP(addr.zip)"
                @input="addr.zip = maskCEP($event.target.value)"
                maxlength="9"
                placeholder="00000-000"
              />
            </div>
          </div>

          <div class="grid-1">
            <div>
              <label>País</label>
              <input v-model="addr.country" placeholder="País" />
            </div>
          </div>

          <div class="text-right">
            <button class="btn-danger" @click.prevent="removeAddress(idx)">
              Remover endereço
            </button>
          </div>
        </div>
      </div>

      <div class="text-right">
        <button type="submit" class="btn-primary">
          Salvar
        </button>
      </div>

    </form>
  </div>
</template>


<script setup>
  import { ref, onMounted } from "vue";
  import api from "@/services/api";
  import { useRoute, useRouter } from "vue-router";
  import { maskCPF, maskCEP } from "@/assets/index.js";

  const route = useRoute();
  const router = useRouter();
  const isEdit = !!route.params.id;

  const profiles = ref([]);

  const form = ref({
    name: "",
    email: "",
    cpf: "",
    profile_id: "",
    addresses: []
  });

  // Carregar perfis e usuário (em caso de edição)
  onMounted(async () => {
    const pr = await api.get("/profiles");
    profiles.value = pr.data.data || pr.data;

    if (isEdit) {
      const res = await api.get(`/users/${route.params.id}`);
      const u = res.data.data || res.data;

      form.value = {
        name: u.name,
        email: u.email,
        cpf: u.cpf,
        profile_id: u.profile?.id,
        addresses: u.addresses.map(a => ({
          street: a.street,
          number: a.number,
          complement: a.complement,
          neighborhood: a.neighborhood,
          city: a.city,
          state: a.state,
          zip: a.zip,
          country: a.country
        }))
      };
    }
  });


  // ADICIONAR ENDEREÇO
  function addAddress() {
    form.value.addresses.push({
      street: "",
      number: "",
      complement: "",
      neighborhood: "",
      city: "",
      state: "",
      zip: "",
      country: ""
    });
  }

  // REMOVER ENDEREÇO
  function removeAddress(idx) {
    form.value.addresses.splice(idx, 1);
  }


  // SALVAR
  async function submit() {
    try {
      if (form.value.addresses.length === 0) {
        alert("Adicione pelo menos um endereço.");
        return;
      }
      if (isEdit) {
        await api.put(`/users/${route.params.id}`, form.value);
      } else {
        await api.post("/users", form.value);
      }

      router.push({ name: "users.list" });

    } catch (err) {
      console.error(err);
      alert(err.response.data.message);
    }
  }
</script>


<style scoped>
  .container {
    max-width: 900px;
    margin: auto;
    padding: 20px;
    font-family: Arial, sans-serif;
  }

  .title {
    margin: 20px 0;
  }

  .form-card {
    background: #fff;
    border: 1px solid #ddd;
    padding: 20px;
    border-radius: 8px;
  }

  /* Seções do formulário */
  .section {
    margin-bottom: 30px;
  }

  .section-title {
    margin-bottom: 15px;
    font-size: 18px;
    font-weight: bold;
  }

  /* GRIDS AJUSTADOS para evitar sobreposição */
  .grid-1,
  .grid-2,
  .grid-3 {
    display: grid;
    gap: 12px;
    width: 100%;
    margin-bottom: 20px;
  }

  .grid-1 {
    grid-template-columns: 1fr;
  }

  .grid-2 {
    grid-template-columns: repeat(2, 1fr);
  }

  .grid-3 {
    grid-template-columns: repeat(3, 1fr);
  }

  label {
    font-weight: bold;
    display: block;
    margin-bottom: 4px;
  }

  input,
  select {
    width: 100%;
    min-width: 0; /* impede overflow */
    padding: 8px;
    border: 1px solid #ccc;
    border-radius: 5px;
    box-sizing: border-box;
  }

  /* CARD DE ENDEREÇO */
  .address-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
  }

  .address-card {
    background: #fafafa;
    border: 1px solid #ddd;
    padding: 15px;
    border-radius: 6px;
    margin-bottom: 20px;
  }

  /* Botões */
  .btn-primary,
  .btn-secondary,
  .btn-danger {
    padding: 6px 12px;
    border: none;
    border-radius: 6px;
    cursor: pointer;
  }

  .btn-primary {
    background: #0069d9;
    color: white;
  }

  .btn-secondary {
    background: #6c757d;
    color: white;
  }

  .btn-danger {
    background: #dc3545;
    color: white;
  }

  /* Ajuste final */
  .text-right {
    text-align: right;
  }
</style>