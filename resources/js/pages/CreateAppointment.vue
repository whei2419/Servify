<template>
  <v-container class="w-100 h-screen main-container">
    <v-card
      class="mx-auto pa-9 mt-4 overflow-y-auto"
      elevation="2"
      max-width="800"
      height="800"
    >
      <h1 class="text-h6 text-md-h5 text-lg-h4 text-center text-bold mb-2">
        Appointment details
      </h1>

      <v-form @submit.prevent >
        <div class="main-container grid-container ">
            <div
          v-for="(input, index) in appointmentData.appointmentData
            .appointmentInputList"
          :key="index"
             >
          <div class="form-item" v-if="input.input.type === 'text'">
            <v-text-field :label="input.input.name"></v-text-field>
          </div>
          <div class="form-item" v-if="input.input.type === 'date'">
            <input type="date">
          </div>
        </div>
        </div>


        <v-btn type="submit" block class="mt-2">Submit</v-btn>
      </v-form>
    </v-card>
  </v-container>
</template>

<script>
import { useAppointmentStore } from "../store/AppointmentStore";
export default {
  setup() {
    const appointmentData = useAppointmentStore();

    return {
      appointmentData,
    };
  },

  data() {
    return {
      date: null,
    };
  },
  computed: {
    formatedDate() {
      return this.date;
    },
  },
};
</script>

<style lang="scss" scoped>
.grid-container {
  display: grid;
  grid-template-columns: repeat(2, 1fr); /* Two columns with equal width */
  gap: 20px; /* Gap between grid items */
}

.grid-item {
  padding: 10px;
  border: 1px solid #ccc;
}
</style>


