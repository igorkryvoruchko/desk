<template>
  <v-sheet width="400" class="mx-auto mt-9">
    <v-card-actions class="justify-center">
      <h2>{{ $t("register") }}</h2>
    </v-card-actions>
    <v-form id="demo" @submit.prevent="signUp">
      <p>
        <v-text-field
          type="name"
          v-model="name"
          :label="$t('name')"
          :error-messages="errorMessages.name"
        />
      </p>
      <p>
        <v-text-field
          type="email"
          v-model="email"
          :label="$t('email')"
          :error-messages="errorMessages.email"
        />
      </p>
      <p>
        <v-text-field
          type="password"
          v-model="password"
          :label="$t('password')"
          :error-messages="errorMessages.password"
        />
      </p>
      <v-card-actions class="justify-center">
        <v-btn type="submit">{{ $t("register") }}</v-btn>
      </v-card-actions>
    </v-form>
  </v-sheet>
</template>

<script>
import { mapActions } from "vuex";
import userRepository from "@/repositories/UserRepository";
export default {
  el: "#demo",
  data: function () {
    return {
      name: null,
      email: null,
      password: null,
      errorMessages: {
        name: null,
        email: null,
        password: null,
      },
    };
  },
  methods: {
    ...mapActions(["setUserData"]),
    signUp() {
      let data = {
        name: this.name,
        email: this.email,
        password: this.password,
      };
      userRepository
        .signUp(data)
        .then((response) => {
          this.setUserData(response);
        })
        .catch((error) => {
          this.errorMessages = error.response.data.errors;
        });
    },
  },
};
</script>
