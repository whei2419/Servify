<template>
    <v-container class="w-100 main-container">
        <Navbar></Navbar>
      <v-card
        class="mx-auto pa-9 mt-16 overflow-y-auto congratulation-card"
        elevation="2"
        max-width="800"
      >

        <h1 class="text-h4 text-md-h5 text-lg-h4 text-center text-bold mb-2">
          Appointment Created!
        </h1>
        <p class="mb-6 text-center">
          Weve sent you an email of your qr please present it to our counter at
          your scheduled date
        </p>
        <v-row class="contents">
            <v-col>
                <div class="qr-cntainer mb-2">
          <qrcode-vue :value="details.code" :size="qrData.size" level="H" />
        </div>
        <p class="text-center">{{ details.code }}</p>

            </v-col>
            <v-col>
                <h4 class="mb-2">Schedule:</h4>
                <p class="schedule">
          <i class="fa-regular fa-calendar-days"></i> Date :
          <span>{{ formattedDate(details.date) }}</span>
        </p>
        <p class="schedule">
          <i class="fa-regular fa-clock"></i> Time : <span>{{ formattedTime(details.date) }}</span>
        </p>
                <h4>Requirements:</h4>
        <ul v-if="details.document.requirement" class="ml-4">
            <li  v-for="item in details.document.requirement" :key="item.id">{{ item.name }}</li>
        </ul>
            </v-col>
        </v-row>





      </v-card>
    </v-container>
</template>

  <script>
  import Navbar from "../components/Navbar.vue";
  import moment from 'moment';
  import { useAppointmentStore } from "../store/AppointmentStore";
  import QrcodeVue from 'qrcode.vue'
  export default {
    setup() {
      const appointmentData = useAppointmentStore();
      const details = appointmentData.getDetails();
      return {
        appointmentData,
        details,
      };
    },
    components: {
        QrcodeVue,
        Navbar
      },

    data() {
      return {
        qrData:{
          value:'dasdasds',
          size:200
        }
      };
    },
    computed: {
      formatedDate() {
        return this.date;
      },
    },
    methods:{
        formattedDate(date) {
        return moment(date).format('YYYY-MM-DD');
      },
      formattedTime(date) {
          return moment(date).format('h:mm:ss A');
      }
    }
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
  .congratulation-card {
    .schedule {
      font-size: 1rem;
      margin-bottom: 10px;
      color: #66bb6a;

    }
    p {
      font-size: $font-sm;

    }
    .icon {
      margin-bottom: 1.5rem;
      i {
        font-size: 4rem;
      }
    }
  }
  .main-container {
    background: #fff;
  }
  .contents {
    align-items: center;
  }
  </style>


