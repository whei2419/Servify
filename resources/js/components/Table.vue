<template>
  <div class="dashboard-container">
    <div class="header-buttons">
      <h1 class="mb-4 title">Appointments</h1>
      <v-btn @click="openDialog" class="button primary-btn">
        Create appointment <i class="fa-solid fa-circle-plus pl-1"></i>
      </v-btn>
    </div>

    <v-card flat class="main-card">
      <div class="form-search">
        <div class="search-icon">
          <i class="fa-solid fa-magnifying-glass"></i>
        </div>
        <input
          class="search"
          @change="getList"
          v-model="search"
          type="text"
          placeholder="Search by keyword"
        />
      </div>
      <v-data-table-server
        v-model:items-per-page="itemsPerPage"
        :headers="headers"
        :items-length="totalItems"
        :items="items"
        :loading="loading"
        :search="search"
        item-value="name"
        @update:options="getList"
        class="border"
        height="550px"
        fixed-header
      >
        <template v-slot:item.name="{ item }">
          {{ getName(item) }}
        </template>

        <template v-slot:item.status="{ item }">
          <span v-if="item.status.name === 'reserve'" class="status reserve">
            <i class="fa-solid fa-list-check pr-2"></i> {{ item.status.name }}
          </span>
          <span
            v-if="item.status.name === 'processing'"
            class="status processing"
          >
            <i class="fa-solid fa-spinner pr-2"></i> {{ item.status.name }}
          </span>
          <span v-if="item.status.name === 'queue'" class="status queue">
            <i class="fa-solid fa-list-ol pr-2"></i> {{ item.status.name }}
          </span>
          <span v-if="item.status.name === 'released'" class="status released">
            <i class="fa-solid fa-circle-check pr-2"></i>{{ item.status.name }}
          </span>
          <span v-if="item.status.name === 'deleted'" class="status deleted">
            <i class="fa-solid fa-circle-minus pr-2"></i>{{ item.status.name }}
          </span>

        </template>

        <template v-slot:item.actions="{ item }">
          <div class="w-100 d-flex ga-3">
            <v-dialog width="800">
              <template v-slot:activator="{ props }">
                <v-btn v-bind="props" size="sm" elevation="0">
                  <i class="fa-solid fa-pen-to-square"></i>
                </v-btn>
              </template>

              <template v-slot:default="{ isActive }">
                <v-card class="view-form">
                  <v-card-text>
                    <div class="modal-title">
                      <div class="title">
                        <h2></h2>
                        <p></p>
                      </div>
                      <button class="close-btn" @click="isActive.value = false">
                        <i class="fa-solid fa-xmark"></i>
                      </button>
                    </div>

                    <edit @reset="getList" :data="item"></edit>
                  </v-card-text>
                </v-card>
              </template>
            </v-dialog>
            <v-dialog width="400">
              <template v-slot:activator="{ props }">
                <v-btn size="sm" elevation="0" v-bind="props">
                  <i class="fa-solid fa-trash"></i>
                </v-btn>
              </template>

              <template v-slot:default="{ isActive }">
                <v-card class="view-form">
                  <v-card-text>
                    <div class="modal-title">
                      <button class="close-btn" @click="isActive.value = false">
                        <i class="fa-solid fa-xmark"></i>
                      </button>
                    </div>
                    <div class="text-center mt-11">
                      Are you sure do you want to delete this appointment?
                    </div>
                    <div class="buttons">
                      <v-btn  @click="isActive.value = false" class="danger-btn"> Cancel </v-btn>
                      <v-btn @click="handleDelete(item.id),isActive.value = false" class="primary-btn"> Confirm </v-btn>
                    </div>
                  </v-card-text>
                </v-card>
              </template>
            </v-dialog>
          </div>
        </template>
      </v-data-table-server>
    </v-card>
    <v-dialog v-model="dialog" max-width="700">
      <v-card>
        <v-card-title>
          <div class="modal-title mt-2">
            <div class="title">
              <h2>Create Appointment</h2>
            </div>
            <button class="close-btn" @click="closeModal()">
              <i class="fa-solid fa-xmark"></i>
            </button>
          </div>
        </v-card-title>
        <v-card-text>
          <v-stepper
            v-if="!complete"
            v-model="step"
            :items="['Appointment time', 'Appointment details']"
          >
            <template v-slot:item.1>
              <div v-if="!dateChecked">
                <div class="mt-2">
                  <p class="small-label">Certificate type</p>
                  <v-select
                    v-model="documentType"
                    label="Choose Document type*"
                    :item-props="itemProps"
                    :items="documentsList"
                    :rules="[(v) => !!v || 'Item is required']"
                  ></v-select>
                </div>
                <p class="small-label">Email</p>
                <v-text-field
                  label="Email address*"
                  placeholder="johndoe@gmail.com"
                  type="email"
                  :rules="emailRules"
                  v-model="email"
                ></v-text-field>

                <div class="date-time mb-11">
                  <div class="form-control">
                    <p class="small-label">Appointment date</p>
                    <input
                      v-model="date"
                      class="date-picker custom-input"
                      type="date"
                      :min="systemDate"
                    />
                  </div>
                  <div class="form-control">
                    <p class="small-label">Appointment time</p>
                    <input
                      v-model="time"
                      min="08:00"
                      max="17:00"
                      class="time-picker custom-input"
                      type="time"
                      @change="validateTime"
                    />
                  </div>
                </div>
                <v-btn
                  :disabled="loading"
                  :loading="loading"
                  block
                  class="text-none primary-btn"
                  size="x-large"
                  variant="flat"
                  append-icon="fa-solid fa-circle-plus"
                  @click="handleClick"
                >
                  Create an Appointment
                </v-btn>
              </div>
            </template>
            <template v-slot:item.2>
              <create @created="handleCreated" v-if="dateChecked"></create>
            </template>
          </v-stepper>
          <success v-if="complete"></success>
        </v-card-text>
      </v-card>
    </v-dialog>
  </div>
</template>

  <script>
import create from "../components/Create.vue";
import success from "../components/success.vue";
import axios from "axios";
import config from "../utils.js";
import moment from "moment";
import { useAppointmentStore } from "../store/AppointmentStore";
import edit from "../components/Edit.vue";
export default {
  components: {
    create,
    success,
    edit,
  },
  data() {
    return {
      complete: false,
      dateChecked: false,
      loading: true,
      itemsPerPage: 25,
      totalItems: 0,
      dialog: false,
      date: null,
      time: null,
      documentsList: [],
      documentType: null,
      email: null,

      search: "",
      headers: [
        { key: "code", title: "Qr code" },
        { key: "date", title: "Date" },
        { key: "name", title: "Name" },
        { key: "status", title: "Status" },
        { key: "email", title: "Email" },
        { key: "document.name", title: "Document type" },
        { key: "actions", title: "" },
      ],
      items: [],
      step: 1,
    };
  },
  methods: {
    handleDelete(id){
        var token = localStorage.getItem("token");
        axios({
          method: "post",
          url: `${config.baseUrl}/appointment/deleteAppointment`,
          headers: {
            Authorization: `Bearer ${token}`,
          },
          data: {
            appointment_id:id,
          },
        })
          .then((res) => {
            this.getList();
          })
          .catch((error) => {
            console.error("Error occurred:", error);
          });
    },
    closeModal() {
      this.dialog = false;
      this.complete = false;
      this.step = 1;
    },
    handleCreated() {
      this.complete = true;
      this.getList();
    },
    handleClick() {
      if (this.time === null || this.date === null) {
        this.errorMessage =
          "Please complete all fields to check available date and time";
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
        documentId: this.documentType.id,
      };

      console.log(this.documentType.input);

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
              this.dateChecked = true;
              this.loading = false;
              this.step = 2;
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
    validateTime() {
      const selectedTime = new Date(`2000-01-01T${this.time}`);
      const startTime = new Date(`2000-01-01T08:00`);
      const endTime = new Date(`2000-01-01T17:00`);

      if (selectedTime < startTime || selectedTime > endTime) {
        this.errorMessage = "Please add a time between 8:00am to 5:00pm";
        this.time = "08:00";
      }
    },
    itemProps(item) {
      return {
        title: item.name,
      };
    },
    openDialog() {
      this.dialog = true;
    },
    closeDialog() {
      this.dialog = false;
    },
    getName(row) {
      const data = JSON.parse(row.values);
      return data[0].value;
    },
    buildJson(data) {
      return JSON.parse(data);
    },
    getList() {
      var token = localStorage.getItem("token");
      axios({
        method: "post",
        url: `${config.baseUrl}/appointment/appointmentList`,
        headers: {
          Authorization: `Bearer ${token}`,
        },
        data: {
          limit: this.itemsPerPage,
          code: this.search,
        },
      })
        .then((res) => {
          this.loading = false;
          this.items = res.data.data;
          this.totalItems = res.data.total;
          this.itemsPerPage = res.data.per_page;
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

<style lang="scss">
.v-card-text {
  padding: 0;
  margin-bottom: 20px;

  .search {
    width: 300px;
    margin-left: auto;
  }
}
.main-card {
  border-radius: 8px !important;
  box-shadow: rgba(149, 157, 165, 0.2) 0px 8px 24px !important;
  padding: 20px !important;
}
.form-search {
  width: 300px;
  margin-left: auto;
  position: relative;
  margin-bottom: 20px;

  .search-icon {
    color: #adadad;
    position: absolute;
    top: 10px;
    left: 10px;
  }

  input {
    outline: 1px solid #cfcfcf;
    border-radius: 4px;
    height: 40px;
    width: 100%;
    padding: 4px 4px 4px 35px;
    color: #707070;
    font-size: 14px;
  }
}
.status {
  padding: 5px 10px;
  border-radius: 4px;
  width: 200px;
  box-shadow: rgba(0, 0, 0, 0.24) 0px 3px 8px;

  color: #fff;

  &.reserve {
    background: #18b1f7;
  }
  &.processing {
    background: #3043bb;
  }
  &.queue {
    background: #f2dd1b;
  }
  &.released {
    background: #73c930;
  }
  &.deleted {
    background: #c93a30;
  }
}
.view-form {
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
      margin-bottom: 8px;
      font-weight: 500;
    }
  }
  .close-btn {
    position: absolute;
    top: 0;
    right: 0;
    z-index: 99;
  }
}
th {
  font-weight: 600 !important;
}
.header-buttons {
  display: flex;
  justify-content: space-between;
  align-items: center;
}
.date-time {
  display: flex;
  width: 100%;
  gap: 10px;

  .form-control {
    width: 100%;
  }
}
.v-stepper-actions {
  display: none !important;
}
.buttons {
    display: flex;
    justify-content: end;
    gap: 10px;
    margin-top:30px;
}
</style>
