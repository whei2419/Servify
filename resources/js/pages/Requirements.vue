<template>
  <v-container class="w-100 h-screen main-container">
    <Navbar></Navbar>
    <div class="container first-container">
      <v-img
        class="mx-auto mb-10"
        max-width="250"
        aspect-ratio="16/9"
        cover
        src="images/documents.jpg"
      ></v-img>
      <p class="text-center mb-4">Dont know what requirements needed for your certificate?</p>
      <v-select
        label="Select"
        :items="[
          'California',
          'Colorado',
          'Florida',
          'Georgia',
          'Texas',
          'Wyoming',
        ]"
      ></v-select>
      <div class="details">
        <p class="text-bold">Requirements</p>
        <ul class="ml-10">
            <li>sdasdasdas</li>
        </ul>
      </div>
    </div>
  </v-container>
</template>

<script>
import Navbar from "../components/Navbar.vue";
export default {
    components:{
        Navbar
    }
};
</script>

<style lang="scss" scoped>
.main-container {
    background: #fff;
}
.first-container {
    max-width: 600px;
    margin-top: 60px;
}
.text-bold {
    font-weight: 600;
}
</style>
