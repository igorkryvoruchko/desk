<template>
  <div class="home">
    <h1>{{ msg }}</h1>
    <p>
      {{ $t("main.text") }}
    </p>
    <h3>{{ $t("main.getting_starting") }}</h3>
    <ul>
      <li>
        <nav>
          <router-link to="/sign-in">{{ $t("login") }}</router-link>
        </nav>
      </li>
      <li>
        <nav>
          <router-link to="/sign-up">{{ $t("register") }}</router-link>
        </nav>
      </li>
    </ul>
  </div>
</template>

<script>
export default {
  // eslint-disable-next-line vue/multi-word-component-names
  name: "Home",
  props: {
    msg: String,
  },
};
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
h1 {
  padding-top: 70px;
}
h3 {
  margin: 40px 0 0;
  padding-top: 50px;
}
ul {
  list-style-type: none;
  padding: 0;
}
li {
  display: inline-block;
  margin: 0 10px;
}
a {
  color: #42b983;
}
p {
  font-size: 25px;
  padding-top: 100px;
  padding-left: 10%;
  padding-right: 10%;
}
</style>
