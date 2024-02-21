<template>
  <v-container class="w-100 h-screen main-container">
    <v-card class="mx-auto pa-9 mt-12" elevation="2" max-width="600">
      <v-img
        class="rounded-circle mx-auto mb-6"
        :width="200"
        :height="200"
        aspect-ratio="1/1"
        cover
        src="images/logo.png"
      ></v-img>
      <h1 class="text-h6 text-md-h5 text-lg-h4 text-center text-bold mb-3">
        Welcome to Servify
      </h1>
      <p color="text-grey" class="text-center mb-6">
        ut perspiciatis unde omnis iste natus error sit voluptatem accusantium
        doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo
        inventore veritatis et quasi architecto beatae vitae d
      </p>
      <p class="text-danger text--red text-center">{{ errorMessage }}</p>
      <div class="mt-2">
        <v-select
          v-model="documentType"
          label="Choose Document type*"
          :item-props="itemProps"
          :items="documentsList"
          :rules="[v => !!v || 'Item is required']"
        ></v-select>
      </div>
      <v-text-field
        label="Email address*"
        placeholder="johndoe@gmail.com"
        type="email"
        :rules="emailRules"
        v-model="email"
      ></v-text-field>

      <div class="date-time mb-11">
        <div class="form-control">
          <input
            v-model="date"
            class="date-picker custom-input"
            type="date"
            :min="systemDate"
          />
        </div>
        <div class="form-control">
          <input v-model="time"  min="08:00" max="17:00" class="time-picker custom-input" type="time" @change="validateTime" />
        </div>
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
import moment from "moment";
import { ref } from "vue";
import { useAppointmentStore } from "../store/AppointmentStore";
export default {
  setup() {
    const appointmentData = useAppointmentStore;
  },
  data() {
    return {
      loading: false,
      date: null,
      time: null,
      documentsList: [],
      documentType: null,
      email: null,
      systemDate: null,
      errorMessage: null,
      emailRules: [
        (value) =>
          /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value) || "Invalid email address.",
      ],
    };
  },
  methods: {
    validateTime() {
      const selectedTime = new Date(`2000-01-01T${this.time}`);
      const startTime = new Date(`2000-01-01T08:00`);
      const endTime = new Date(`2000-01-01T17:00`);

      if (selectedTime < startTime || selectedTime > endTime) {
        this.time = '08:00';
      }

      this.errorMessage = 'Please add a time between 8:00am to 5:00pm'
    },
    itemProps(item) {
      return {
        title: item.name,
      };
    },
    handleClick() {
        if(this.time === null || this.date === null){
            this.errorMessage = 'Please complete all fields to check available date and time';
            return;
        }
      const time = moment(this.time, "HH:mm")
        .add(0, "seconds")
        .format("HH:mm:ss");

      const appointmentData = {
        appointmentTime: time,
        appointmentDate: this.date,
        appointmentEmail: this.email,
        appointmentInputList: this.documentType.input,
        documentId:this.documentType.id
      };


      console.log( this.documentType.input);

      this.loading = true;
      const dateTime = `${this.date} ${time}`;

      axios({
        method: "post",
        url: `${config.baseUrl}/checkTimeframe`,
        data: {
          datetime: dateTime,
        },
      })
        .then((res) => {
          this.loading = false;
          if (res.data.is_available) {
            this.loading = true;
            setTimeout(() => {
                useAppointmentStore().saveData(appointmentData);
                 this.$router.push({ name: "Appointment" });
            }, 1000);
          } else {
            this.errorMessage =
              "No available slots in this date please choose different date";
          }
        })
        .catch((error) => {
          console.error("Error occurred:", error);
        });
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
    this.systemDate = moment().format("YYYY-MM-DD");
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
}
.text-danger {
    color: red;
    font-size: .9rem;
}
</style>
