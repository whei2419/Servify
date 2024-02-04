<template>
  <v-container class="w-100 main-container">
    <h1 class="text-h6 text-md-h5 text-lg-h4 text-center text-bold mb-2 mt-11">
      Appointment details
    </h1>
    <v-card
      class="mx-auto pa-9 mt-4 overflow-y-auto"
      elevation="2"
      max-width="800"
    >
      <v-form @submit.prevent>
        <div class="section my-6">
          <div
            class="form-control"
            v-for="(input, index) in formattedInputs.text"
            :key="index"
          >
            <label class="mr-2" :for="input.input.name">{{
              input.input.name
            }}</label>
            <input class="input-text" :name="input.input.name" type="text" />
          </div>
          <div v-if="formattedInputs.date && formattedInputs.date.length !== 0">
            <div
              class="form-control"
              v-for="(input, index) in formattedInputs.date"
              :key="index"
            >
              <label class="mr-2" :for="input.input.name">{{
                input.input.name
              }}</label>
              <input class="input-date" :name="input.input.name" type="date" />
            </div>
          </div>
          <div
            v-if="formattedInputs.number && formattedInputs.number.length !== 0"
          >
            <div
              class="form-control"
              v-for="(input, index) in formattedInputs.number"
              :key="index"
            >
              <label class="mr-2" :for="input.input.name">{{
                input.input.name
              }}</label>
              <input
                class="input-text"
                :name="input.input.name"
                type="number"
              />
            </div>
          </div>
        </div>
        <v-divider></v-divider>
        <div class="section-radio my-6">
          <div
            class="form-control"
            v-for="(input, index) in formattedInputs.radio"
            :key="index"
          >
            <input
              class="input-radio"
              type="radio"
              id="option2"
              :name="input.input.type"
              :value="input.input.name"
            />
            <label class="ml-2">{{ input.input.name }}</label>
          </div>
        </div>
        <v-btn
          type="submit"
          block
          color="indigo-darken-3"
          size="x-large"
          variant="flat"
          class="mt-2"
          >Submit details</v-btn
        >
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
      formattedInputs: appointmentData.formatInputs,
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
.section {
  display: grid;
  grid-template-columns: repeat(2, 1fr); /* Two columns with equal width */
  gap: 20px; /* Gap between grid items */
}
.section-radio{
    display: flex;
    gap: 20px;
}

.form-control {
  .input-text,
  .input-date {
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


