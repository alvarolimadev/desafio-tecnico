import { createRouter, createWebHistory } from "vue-router";

import UserList from "../views/Users/List.vue";
import UserForm from "../views/Users/Form.vue";
import UserShow from "../views/Users/Show.vue";

const routes = [
  { path: "/", name: "users.list", component: UserList },

  { path: "/users/create", name: "users.create", component: UserForm },

  { path: "/users/:id", name: "users.show", component: UserShow, props: true },

  { path: "/users/:id/edit", name: "users.edit", component: UserForm, props: true },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

export default router;
