<template>
    <v-container class="w-100 h-screen main-container">
      <v-card class="mx-auto pa-9 mt-12" elevation="2" max-width="600">
        <v-img
          class="rounded-circle mx-auto mb-6"
          :width="200"
          :height="200"
          aspect-ratio="1/1"
          cover
          src="https://cdn.vuetifyjs.com/images/parallax/material.jpg"
        ></v-img>
        <h1 class="text-h6 text-md-h5 text-lg-h4 text-center text-bold mb-3">
          Welcome to Servify
        </h1>
        <p color="text-grey" class="text-center mb-6">
          ut perspiciatis unde omnis iste natus error sit voluptatem accusantium
          doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo
          inventore veritatis et quasi architecto beatae vitae d
        </p>
        <v-text-field
          label="Email address"
          placeholder="johndoe@gmail.com"
          type="email"
          v-model="email"
        ></v-text-field>

        <div class="date-time mb-5">
          <div class="form-control">
            <input v-model="date" class="date-picker" type="date" />
          </div>
          <div class="form-control">
            <input v-model="time" class="time-picker" type="time" />
          </div>
        </div>
        <div class="mt-2 mb-8">
          <v-select
            v-model="documentType"
            label="Choose Document type"
            :item-props="itemProps"
            :items="documentsList"
          ></v-select>
        </div>

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
  import axios from "axios";
  import config from "../utils";
  import { ref } from "vue";
  import { useAppointmentStore } from "../store/AppointmentStore";
  export default {
    setup(){
       const appointmentData = useAppointmentStore
    },
    data() {
      return {
        loading: false,
        date: null,
        time: null,
        documentsList: [],
        documentType: null,
        email: null
      };
    },
    methods: {
      itemProps(item) {
        return {
          title: item.name,
        };
      },
      handleClick() {
        const appointmentData = {
          appointmentTime: this.time,
          appointmentDate: this.date,
          appointmentEmail: this.email,
          appointmentInputList: this.documentType.input
        };

        this.loading = true;
        setTimeout(() => {
          useAppointmentStore().saveData(appointmentData);
          this.loading = false;
          this.$router.push({ name: "Appointment" });
        }, 1000);
      },
      getDocuments() {
        axios({
          method: "get",
          url: `${config.baseUrl}/document`,
        })
          .then((res) => {
            this.documentsList = res.data.documents;
          })
          .catch((error) => {
            console.error("Error occurred:", error);
          });
      },
    },
    created() {
      this.getDocuments();
    },
  };
  </script>

  <style lang="scss" scoped>
  .date-time {
    display: flex;
    justify-content: center;
    gap: 10px;
    box-sizing: border-box;
    width: 100%;

    .form-control {
      width: 100%;
    }

    input {
      padding: 5px 20px;
      border-bottom: 1px solid rgb(133, 133, 133);
      border-radius: 0px;
      background: transparent;
      border-top-left-radius: 5px;
      border-top-right-radius: 5px;
      height: 50px;
      width: 100%;
      background: #2a2a2a;
      &:focus {
        background: rgb(133, 133, 133);
        outline: none;
        border-bottom: 2px solid #ffffff;
        transition: all 0.5 ease-in-out;
      }
    }
  }
  </style>
