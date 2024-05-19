import Client from "./Clients/AxiosClient";
import store from '../store'
export default {
  signIn(payload) {
    return Client.post(`${store.getters.locale}/login_check`, payload);
  },
  signUp(payload) {
    return Client.post(`${store.getters.locale}/signup`, payload);
  },
};
