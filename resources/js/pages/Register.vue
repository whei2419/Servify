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
      <h1 class="text-center mb-4">Register Account</h1>
      <v-form @submit.prevent="handleSubmit">
        <v-text-field
          v-model="formData.fullName"
          :rules="fullNameRules"
          label="Full name"
        ></v-text-field>
        <v-text-field
          v-model="formData.email"
          :rules="emailRules"
          label="Email"
        ></v-text-field>
        <v-text-field
          v-model="formData.password"
          :rules="passwordRules"
          label="Password"
        ></v-text-field>
        <v-text-field
          v-model="formData.confirmPassword"
          :rules="confirmPasswordRules"
          label="Confirm password"
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
import debounce from "lodash/debounce";
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
      confirmPasswordRules: [
        (value) =>
          (value && value === this.formData.password) || "Passwords do not match.",
      ],
    };
  },
  methods: {
    handleSubmit() {
        axios({
                method: "post",
                url: `${config.baseUrl}/register`,
                data: {
                    name: this.formData.fullName,
                    email: this.formData.email,
                    password: this.formData.password
                },
            })
            .then((res) => {
                console.log(res)
            })
            .catch((error) => {
                console.error('Error occurred:', error);
            });
    },
  },
};
</script>

<style></style>
