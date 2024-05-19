<template>
  <div class="sign_in">
    <SignIn></SignIn>
  </div>
</template>

<script>
// @ is an alias to /src
import SignIn from "@/components/SignIn.vue";

export default {
  name: "SignInView",
  components: { SignIn },
};
</script>
