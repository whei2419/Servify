<template>
  <v-container class="w-100 main-container mt-11">
    <Navbar></Navbar>
    <v-card
      class="mx-auto pa-9 mt-16 overflow-y-auto form"
      elevation="2"
      max-width="800"
    >
      <div class="modal-title">
        <div class="title">
          <h2>Appointment details</h2>
          <p>
            Fill out this form to complete your
            appointment
          </p>
        </div>
      </div>

      <v-form
        class="form-border"
        v-modal="formData"
        @submit.prevent="handleSubmit"
      >
        <div class="section my-6">
          <div
            class="form-control"
            v-for="(input, index) in formattedInputs.text"
            :key="index"
          >
            <label class="mr-2" :for="input.input.name">{{
              input.input.name
            }}</label>
            <input
              class="input-text custom-input"
              :name="input.input.name"
              type="text"
              :data-id="input.input.id"
              required
            />
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
              <input
                class="input-date custom-input"
                :name="input.input.name"
                :data-id="input.input.id"
                type="date"
                required
              />
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
                class="input-text custom-input"
                :name="input.input.name"
                :data-id="input.input.id"
                type="number"
                required
              />
            </div>
          </div>
        </div>
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
          :loading="loading"
          type="submit"
          block
          size="x-large"
          variant="flat"
          class="mt-2 primary-btn"
          >Submit details</v-btn
        >
      </v-form>
    </v-card>
  </v-container>
</template>

<script>
import Navbar from "../components/Navbar.vue";
import axios from "axios";
import config from "../utils.js";
import { useAppointmentStore } from "../store/AppointmentStore";
export default {
  setup() {
    const appointmentData = useAppointmentStore();

    return {
      appointmentData,
      formattedInputs: appointmentData.formatInputs,
    };
  },
  components: {
    Navbar,
  },
  data() {
    return {
      date: null,
      formData: {},
      selectedValue: null,
      isComplete: true,
      resDate: null,
      loading: false,
    };
  },
  computed: {
    formatedDate() {
      return this.date;
    },
  },
  methods: {
    handleSubmit(values) {
      this.loading = true;
      const userDetails = useAppointmentStore().appointmentData;
      const documentId = userDetails.documentId;
      const date = `${userDetails.appointmentDate} ${userDetails.appointmentTime}`;
      const processData = [];
      const inputs = values.target;

      for (const item of inputs) {
        if (item.type != "radio" && item.type != "submit") {
          processData.push({
            id: item.dataset.id,
            value: item.value,
            type: item.type,
            name: item.name,
          });
        }
      }

      axios({
        method: "post",
        url: `${config.baseUrl}/appointment`,
        data: {
          date: date,
          email: userDetails.appointmentEmail,
          values: JSON.stringify(processData),
          document_id: documentId,
        },
      })
        .then((res) => {
          useAppointmentStore().saveDetails(res.data.appointment);
          this.$router.push({ name: "Details" });
          this.loading = false;
        })
        .catch((error) => {
          console.error("Error occurred:", error);
        });
    },
  },
  created() {
    const formInputs = useAppointmentStore().formatInputs;
  },
};
</script>

<style lang="scss" scoped>
.qr-cntainer {
  display: flex;
  justify-content: center;
  flex-direction: row;
  gap: 10px;

  canvas {
    padding: 1rem;
    background: #fff;
  }
}
.section {
  display: grid;
  grid-template-columns: repeat(2, 1fr); /* Two columns with equal width */
  gap: 20px; /* Gap between grid items */
}
.section-radio {
  display: flex;
  gap: 20px;
}

.form-control {
  label {
    font-size: $font-sm;
  }
}
.congratulation-card {
  .schedule {
    font-size: 1rem;
    margin: 10px;
    color: #66bb6a;

    span {
      color: white;
    }
  }
  p {
    font-size: $font-sm;
    text-align: center;
  }
  .icon {
    margin-bottom: 1.5rem;
    i {
      font-size: 4rem;
    }
  }
}
.form {
  border-radius: 8px !important;
}
.modal-title {
    display: flex;
    justify-content: space-between;
    align-items: center;
    width: 100%;
    position: relative;
    .title {
        h2 {
            margin-bottom: 2px;
            font-size: 28px;

        }
        p {
            font-size: 14px;
            margin-bottom: 18px;
            font-weight: 500;
        }
    }
}
</style>


