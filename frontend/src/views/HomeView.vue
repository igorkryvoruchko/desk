<template>
  <div class="home">
    <Home msg="Welcome to Desc, your ultimate restaurant companion app!" />
  </div>
</template>

<script>
// @ is an alias to /src
import Home from "@/components/Home.vue";

export default {
  name: "HomeView",
  components: {
    Home,
  },
};
</script>
<style>
.home {
  /* Background pattern from Toptal Subtle Patterns */
  height: 815px;
  width: 100%;
  background-image: url("../assets/table-made-with-planks_1253-2.avif");
  background-repeat: no-repeat;
  background-position: center;
  background-size: cover;
}
</style>
