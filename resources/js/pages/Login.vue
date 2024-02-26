<template>
    <div class="main-container">
        <v-img
          class="rounded-circle mx-auto mt-16"
          :width="180"
          :height="180"
          aspect-ratio="1/1"
          cover
          src="images/logo.png"
        ></v-img>
      <div class="mx-auto pa-6 mt-6 login-container" >
        <h2 class="text-start">Sign in</h2>
        <p class="form-text" >Lorem ipsum dolor sit amet consectetur adipisicing elit. Saepe, veritatis?</p>
        <p v-if="message" class="error-message text-center mb-1">{{ message }}</p>
        <v-form class="form form-border" @submit.prevent="handleSubmit">
        <p class="small-label">Email</p>
          <v-text-field
            v-model="formData.email"
            :rules="emailRules"
            label="Email"
            type="email"
          ></v-text-field>
          <p class="small-label">Password</p>
          <v-text-field
            class="mb-0"
            v-model="formData.password"
            :rules="passwordRules"
            label="Password"
            type="password"
          ></v-text-field>
          <p class="forgot-password">Forgot Password?</p>
          <v-btn
            type="submit"
            variant="flat"
            block
            size="x-large"
            class="mt-5 primary-btn"
            >Submit</v-btn
          >
        </v-form>
      </div>
      <p class="copyright">COPYRIGHT @Servify 2024</p>
    </div>
</template>

<script>
  import axios from 'axios'
  import config from "../utils.js"
  export default {
    data() {
      return {
        message:null,
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
                  this.$router.push({ name: "Dashboard",});
              })
              .catch((error) => {
                  console.error('Error occurred:', error);
                  this.message = error.message;
              });
      },
    },
  };

</script>

<style lang="scss" scope>
.login-container {
    border-radius: 10px;
}
.v-field {
    padding-top: 0 !important;
}
.v-field__field {
    label {
        display: none !important;
    }
}
.v-field__input{
    padding-top: 0 !important;
}

.main-container {
    background: #FFFEF7;
    width: 100%;
    box-sizing: border-box;
    height: 100vh;
    overflow-y: hidden;

    .login-container {
        background: #fff;
        padding: 20px 30px 40px 30px !important;
        max-width: 550px;
        box-shadow: rgba(149, 157, 165, 0.2) 0px 8px 24px;
    }

}
.forgot-password{
    font-size: 12px;
    text-align: end;
    font-weight: 500 !important;
}
.form-text {
    font-size: 14px;
    margin-bottom: 15px;
    font-weight: 500;
}

</style>

