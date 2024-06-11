<template>
  <v-app>
    <v-main>
      <v-toolbar
        dark
        prominent
        image="https://cdn.vuetifyjs.com/images/backgrounds/vbanner.jpg"
      >
        <v-app-bar-nav-icon></v-app-bar-nav-icon>

        <v-toolbar-title
          ><router-link to="/" class="home-link"
            >Desc</router-link
          ></v-toolbar-title
        >

        <v-spacer></v-spacer>

        <v-select
            v-model="language"
            :items="languageItems"
            :label="$t('language')"
            outlined
            @update:model-value="changeLocale"
        ></v-select>
        <h3 class="home-link">{{ this.user.name }}</h3>
        <v-btn v-if="this.user.name" @click="signOutAction" color="#FFFFFF">
          {{ $t("sign_out") }}
        </v-btn>
        <v-btn href="/sign-in" v-else color="#FFFFFF">{{ $t('login') }}</v-btn>
      </v-toolbar>
      <router-view />
    </v-main>
  </v-app>
</template>

<script>
import { mapActions, mapState } from "vuex";
import {useI18n} from "vue-i18n";

export default {
  name: "App",

  data: () => ({
    language: null,
    languageItems: ["de", "en"],
  }),
  setup() {
    const { t } = useI18n({
      inheritLocale: true,
      useScope: "local",
    });

    return {
      t
    };
  },
  computed: {
    ...mapState({
      user: "user",
      locale: "locale",
    }),
  },

  methods: {
    ...mapActions(["signOutAction", "setLocale"]),
    changeLocale() {
      this.$i18n.locale = this.language;
      this.setLocale(this.language);
    },
  },
  beforeMount() {
    this.language = this.locale
  }
};
</script>

<style>
.home-link {
  color: antiquewhite;
}
.v-select__selection,
.v-select__selection--comma,
.v-select.v-text-field input {
  color: white !important;
}
</style>
