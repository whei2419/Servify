<template>
    <v-container>
      <v-card class="mx-auto pa-6 mt-11" elevation="2" max-width="600">
        <v-img
          class="rounded-circle mx-auto mb-5"
          :width="180"
          :height="180"
          aspect-ratio="1/1"
          cover
          src="https://cdn.vuetifyjs.com/images/parallax/material.jpg"
        ></v-img>
        <h1 class="text-center mb-4">Sign in your Account</h1>
        <v-form @submit.prevent="handleSubmit">
          <v-text-field
            v-model="formData.email"
            :rules="emailRules"
            label="Email"
            type="email"
          ></v-text-field>
          <v-text-field
            v-model="formData.password"
            :rules="passwordRules"
            label="Password"
            type="password"
          ></v-text-field>
          <v-btn
            type="submit"
            variant="flat"
            color="indigo-darken-3"
            block
            size="x-large"
            class="mt-9"
            >Submit</v-btn
          >
        </v-form>
      </v-card>
    </v-container>
</template>

<script>
  import axios from 'axios'
  import config from "../utils.js"
  export default {
    data() {
      return {
        formData: {
          fullName: "",
          email: "",
          password: "",
          confirmPassword: "",
        },
        fullNameRules: [(value) => !!value || "Full name is required."],
        emailRules: [
          (value) =>
            /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value) || "Invalid email address.",
        ],
        passwordRules: [
          (value) =>
            (value && value.length >= 8) ||
            "Password must be at least 8 characters long.",
        ],
      };
    },
    methods: {
      handleSubmit() {
          axios({
                  method: "post",
                  url: `${config.baseUrl}/login`,
                  data: {
                      email: this.formData.email,
                      password: this.formData.password
                  },
              })
              .then((res) => {
                  console.log(res)
                  localStorage.setItem('token', res.data.token);
                  this.$router.push({ name: "Scanner",});
              })
              .catch((error) => {
                  console.error('Error occurred:', error);
              });
      },
    },
  };

</script>

