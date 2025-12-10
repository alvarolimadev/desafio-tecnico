<template>
  <div class="container">

    <div class="header-actions">
      <h2>Pesquisa de Usuários</h2>
      <button class="btn-primary" @click="$router.push('/users/create')">Novo Usuário</button>
    </div>

    <!-- FILTROS -->
    <div class="card filters-card">
      <h3>Filtros</h3>
      <form class="filters-grid" @submit.prevent="search">

        <div class="field">
          <label>Nome</label>
          <input v-model="filters.name" placeholder="Nome" />
        </div>

        <div class="field">
          <label>CPF</label>
          <input
            :value="filters.cpf"
            @input="filters.cpf = maskCPF($event.target.value)"
            maxlength="14"
            placeholder="CPF"
            required
          />
        </div>

        <div class="field">
          <label>Data Inicial</label>
          <input type="date" v-model="filters.date_from" />
        </div>

        <div class="field">
          <label>Data Final</label>
          <input type="date" v-model="filters.date_to" />
        </div>

        <div class="filter-buttons">
          <button type="submit" class="btn-primary">Pesquisar</button>
          <button @click.prevent="reset" class="btn-secondary">Limpar</button>
        </div>

      </form>
    </div>

    <!-- TABELA -->
    <div class="card table-card">
      <table class="users-table">
        <thead>
          <tr>
            <th>Nome</th>
            <th>CPF</th>
            <th>Email</th>
            <th>Perfil</th>
            <th>Ações</th>
          </tr>
        </thead>

        <tbody>
          <tr v-for="u in users" :key="u.id">
            <td>{{ u.name }}</td>
            <td>{{ maskCPF(u.cpf) }}</td>
            <td>{{ u.email }}</td>
            <td>{{ u.profile?.name }}</td>
            <td class="actions">
              <router-link :to="{ name: 'users.show', params: { id: u.id }}" class="link">Detalhe</router-link>
              <router-link :to="{ name: 'users.edit', params: { id: u.id }}" class="link">Editar</router-link>
              <button class="btn-danger" @click="remove(u.id)">Excluir</button>
            </td>
          </tr>

          <tr v-if="users.length === 0">
            <td colspan="5" class="empty">Nenhum registro encontrado.</td>
          </tr>
        </tbody>
      </table>
    </div>

    <pagination :meta="meta" @change="pageChanged" />

  </div>
</template>

<script setup>
  import { ref } from 'vue';
  import api from '@/services/api';
  import { maskCPF } from "@/assets/index.js";

  const users = ref([]);
  const meta = ref({});
  const filters = ref({ name:'', cpf:'', date_from:'', date_to:'' });

  async function fetch(page = 1) {
    const params = { ...filters.value, page, per_page: 10 };
    const res = await api.get('/users', { params });
    users.value = res.data.data;
    meta.value = res.data.meta || {};
  }

  function search() { fetch(1); }
  function reset() { filters.value = { name:'', cpf:'', date_from:'', date_to:'' }; fetch(1); }
  function pageChanged(page) { fetch(page); }

  async function remove(id) {
    if (!confirm('Excluir usuário?')) return;
    await api.delete(`/users/${id}`);
    fetch(meta.value.current_page || 1);
    alert('Usuário excluído com sucesso.');
  }

  fetch();
</script>

<style scoped>
  .container {
    padding: 20px;
    font-family: Arial, sans-serif;
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

  /* Cabeçalho */
  .header-actions {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
  }

  /* Cartões */
  .card {
    background: #f8f9fa;
    padding: 20px;
    border-radius: 8px;
    margin-bottom: 25px;
    border: 1px solid #ddd;
  }

  /* Grid dos filtros */
  .filters-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 15px;
  }

  .field {
    display: flex;
    flex-direction: column;
  }

  .filter-buttons {
    display: flex;
    gap: 10px;
    align-items: flex-end;
  }

  /* Tabela */
  .users-table {
    width: 100%;
    border-collapse: collapse;
  }

  .users-table th,
  .users-table td {
    padding: 10px;
    border-bottom: 1px solid #ddd;
  }

  .users-table th {
    background: #eee;
    text-align: left;
  }

  .actions {
    display: flex;
    gap: 10px;
  }

  .link {
    color: #0069d9;
    text-decoration: none;
    display: flex;
    align-items: center;
  }

  label {
    font-weight: bold;
    display: block;
    margin-bottom: 4px;
  }

  .btn-primary,
  .btn-secondary,
  .btn-danger {
    padding: 6px 12px;
    cursor: pointer;
    border-radius: 4px;
    border: none;
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

  .empty {
    text-align: center;
    padding: 20px;
    color: #666;
  }
</style>
