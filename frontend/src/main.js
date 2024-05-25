import { createApp } from "vue";
import App from "./App.vue";
import router from "./router";
import store from "./store";
// Vuetify
import "vuetify/styles";
import { createVuetify } from "vuetify";
import * as components from "vuetify/components";
import * as directives from "vuetify/directives";
import { createI18n, useI18n } from "vue-i18n";
import { languages } from "./i18n";

const messages = Object.assign(languages);
const i18n = createI18n({
  legacy: false,
  locale: store.getters.locale || 'de',
  fallbackLocale: 'de',
  messages
});

const vuetify = createVuetify({
  components,
  directives,
});

createApp(App, {
  setup() {
    const {t} = useI18n()
    return {t}
  }
}).use(router).use(store).use(vuetify).use(i18n).mount("#app");
