<template>
  <v-container class="w-100 main-container">


    <v-card
      class="mx-auto pa-9 mt-4 overflow-y-auto"
      elevation="2"
      max-width="800"
    >
    <h1 class="text-h4 text-md-h5 text-lg-h4 text-center text-bold mb-2">
      Appointment details
    </h1>
    <p class="mb-6">Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo</p>
    <v-divider></v-divider>
      <v-form v-modal="formData" @submit.prevent="handleSubmit">
        <div class="section my-6">
          <div
            class="form-control"
            v-for="(input, index) in formattedInputs.text"
            :key="index"
          >
            <label class="mr-2" :for="input.input.id">{{
              input.input.name
            }}</label>
            <input class="input-text" :name="input.input.id" type="text" required />
          </div>
          <div v-if="formattedInputs.date && formattedInputs.date.length !== 0">
            <div
              class="form-control"
              v-for="(input, index) in formattedInputs.date"
              :key="index"
            >
              <label class="mr-2" :for="input.input.id">{{
                input.input.name
              }}</label>
              <input class="input-date" :name="input.input.id" type="date" required />
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
              <label class="mr-2" :for="input.input.id">{{
                input.input.name
              }}</label>
              <input
                class="input-text"
                :name="input.input.id"
                type="number"
                required
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
              :name="input.input.type"
              :value="input.input.id"
              v-model="selectedValue"
              required
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
      formData:{},
      selectedValue:null
    };
  },
  computed: {
    formatedDate() {
      return this.date;
    },
  },
  methods:{
    handleSubmit(values){
        const processData = {};
        const inputs = values.target;
        for(const item of inputs){
          if(item.type != 'radio' && item.type != 'submit' ){
            processData[item.name] = item.value;
          }
        }
        if(!this.selectedValue){
          processData[this.selectedValue] = true;
        }

        console.log(processData);
    },
  },
  created() {
    const formInputs = useAppointmentStore().formatInputs;

    console.log(formInputs);

  }
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


