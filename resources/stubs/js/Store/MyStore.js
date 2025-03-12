import { createStore } from 'vuex';

export const store = createStore({
  state() {
    return {
      count: 0,
      user: null,
      notifications: [],
      // Adicione outros estados conforme necessário
    };
  },
  mutations: {
    increment(state) {
      state.count++;
    },
    setUser(state, user) {
      state.user = user;
    },
    addNotification(state, notification) {
      state.notifications.push(notification);
    },
    clearNotifications(state) {
      state.notifications = [];
    },
    // Adicione outras mutações conforme necessário
  },
  actions: {
    // Exemplo de ação assíncrona
    async fetchUser({ commit }) {
      try {
        const response = await axios.get('/api/user');
        commit('setUser', response.data);
      } catch (error) {
        console.error('Erro ao buscar usuário:', error);
      }
    },
    // Adicione outras ações conforme necessário
  },
  getters: {
    notificationCount(state) {
      return state.notifications.length;
    },
    // Adicione outros getters conforme necessário
  }
});