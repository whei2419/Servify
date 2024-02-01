<template>
  <v-container class="w-100 h-screen main-container">
    <v-card class="mx-auto pa-9 mt-12" elevation="2" max-width="600">
      <v-img
        class="rounded-circle mx-auto mb-11"
        :width="200"
        :height="200"
        aspect-ratio="1/1"
        cover
        src="https://cdn.vuetifyjs.com/images/parallax/material.jpg"
      ></v-img>
      <h1 class="text-h6 text-md-h5 text-lg-h4 text-center text-bold mb-3">
        Welcome to Servify
      </h1>
      <p color="text-grey" class="text-center mb-10">
        ut perspiciatis unde omnis iste natus error sit voluptatem accusantium
        doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo
        inventore veritatis et quasi architecto beatae vitae d
      </p>
      <v-btn
        :disabled="loading"
        :loading="loading"
        block
        class="text-none mb-4"
        color="indigo-darken-3"
        size="x-large"
        variant="flat"
        append-icon="fa-solid fa-circle-plus"
        @click="handleClick"
      >
        Create an Appointment
      </v-btn>
    </v-card>
  </v-container>
</template>
<script>
export default {
  data() {
    return {
      loading: false,
    };
  },
  methods: {
    handleClick() {
      this.loading = true;
      setTimeout(() => {
        this.$router.push({ name: "Appointment" });
      }, 1000);
    },
  },
};
</script>
