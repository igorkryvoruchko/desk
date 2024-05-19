import { createStore } from "vuex";
import UserRepository from "@/repositories/UserRepository";
import VuexPersistence from "vuex-persist";

const vuexLocal = new VuexPersistence({
  storage: window.localStorage,
});
export default createStore({
  state: {
    user: {},
    token: null,
    locale: null,
  },
  getters: {
    user(state) {
      return state.user;
    },
    token(state) {
      return state.token;
    },
    locale(state){
      return state.locale
    }
  },
  mutations: {
    setUserToState(state, user) {
      state.user = user;
    },
    setTokenToState(state, token) {
      state.token = token;
    },
    setLocaleToState(state, locale) {
      state.locale = locale;
    },
    signOut(state) {
      state.token = null;
      localStorage.setItem("token", null);

      state.user = {};
      localStorage.setItem("user", "{}");
    },
  },
  actions: {
    getUserData({ commit }, data) {
      return UserRepository.signIn(data).then((response) => {
        commit("setUserToState", response.data.data.user);
        commit("setTokenToState", response.data.data.token);

        return response.data.data;
      });
    },
    setUserData({ commit }, response) {
      commit("setUserToState", response.data.data.user);
      commit("setTokenToState", response.data.data.token);
    },
    signOutAction({ commit }) {
      commit("signOut");
    },
    setLocale({ commit }, locale) {
      commit("setLocaleToState", locale)
    },
  },
  modules: {},
  plugins: [vuexLocal.plugin],
});
