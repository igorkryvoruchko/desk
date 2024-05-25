<template>
  <v-sheet width="400" class="mx-auto mt-9">
    <v-card-actions class="justify-center">
      <h2>{{ $t("login") }}</h2>
    </v-card-actions>
    <v-form id="demo" @submit.prevent="signIn">
      <p>
        <v-text-field
          type="email"
          v-model="email"
          label="Email"
          :error-messages="errorMessages.email"
        />
      </p>
      <p>
        <v-text-field
          type="password"
          v-model="password"
          label="Password"
          :error-messages="errorMessages.password"
        />
      </p>
      <v-card-actions class="justify-center">
        <v-btn type="submit">{{ $t("login") }}</v-btn>
      </v-card-actions>
    </v-form>
  </v-sheet>
</template>

<script>
import { mapActions, mapGetters } from "vuex";

export default {
  el: "#demo",
  data: function () {
    return {
      email: null,
      password: null,
      errorMessages: {
        form: null,
        email: null,
        password: null,
      },
    };
  },
  computed: {
    ...mapGetters(["user"]),
  },
  methods: {
    ...mapActions(["getUserData"]),
    signIn() {
      let data = {
        email: this.email,
        password: this.password,
      };
      this.getUserData(data).catch((error) => {
        this.errorMessages = error.response.data.errors;
      });
    },
  },
};
</script>
