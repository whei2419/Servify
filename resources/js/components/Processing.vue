<template>
  <v-container>
    <div v-for="(item, index) in list" :key="index" class="queue">
      <v-card max-width="900" v-if="item.status_id === 3" class="mb-4 mx-auto">
        <template v-slot:title>
          <div class="title-container">
            <div class="title-text">
              <h2>Appointment date : {{ fomatDate }}</h2>
              <p class="subtitle">Code: {{ userData.code }}</p>
            </div>

            <div class="options">
              <v-btn @click="isEdit = !isEdit">
                <span v-if="!isEdit"> Edit </span>
                <span v-if="isEdit"> Cancel Edit </span>
                <i v-if="isEdit" class="fa-solid fa-circle-xmark ml-2"></i>
                <i v-if="!isEdit" class="fa-regular fa-pen-to-square ml-2"></i>
              </v-btn>
              <v-btn @click="download" color="indigo-darken-3">
                <span>Download</span>
                <i class="fa-solid fa-down-long ml-2"></i>
              </v-btn>
            </div>
          </div>
        </template>
        <v-divider></v-divider>
        <div class="content pa-5">
          <v-form class="w-100" @submit.prevent="handleSubmit">
            <div class="section my-6">
              <div
                class="form-control"
                v-for="(input, index) in formatedInputs.text"
                :key="index"
              >
                <label class="mr-2" :for="input.name">{{ input.name }}</label>
                <input
                  class="input-text custom-input"
                  :name="input.name"
                  type="text"
                  :data-id="input.id"
                  :value="input.value"
                  required
                  :disabled="!isEdit"
                />
              </div>
              <div
                v-if="formatedInputs.date && formatedInputs.date.length !== 0"
              >
                <div
                  class="form-control"
                  v-for="(input, index) in formatedInputs.date"
                  :key="index"
                >
                  <label class="mr-2" :for="input.id">{{ input.name }}</label>
                  <input
                    class="input-date custom-input"
                    :name="input.id"
                    type="date"
                    :value="input.value"
                    :disabled="!isEdit"

                    required
                  />
                </div>
              </div>
              <div
                v-if="
                  formatedInputs.number && formatedInputs.number.length !== 0
                "
              >
                <div
                  class="form-control"
                  v-for="(input, index) in formatedInputs.number"
                  :key="index"
                >
                  <label class="mr-2" :for="input.id">{{ input.name }}</label>
                  <input
                    class="input-text custom-input"
                    :name="input.name"
                    type="number"
                    :value="input.value"
                    :disabled="!isEdit"
                    required
                  />
                </div>
              </div>
            </div>
            <v-divider></v-divider>
            <div class="section-radio my-6">
              <div
                class="form-control"
                v-for="(input, index) in formatedInputs.radio"
                :key="index"
              >
                <input
                  class="input-radio"
                  type="radio"
                  :name="input.type"
                  :value="input.id"
                  v-model="selectedValue"
                  :disabled="!isEdit"
                  required
                />
                <label class="ml-2">{{ input.input.name }}</label>
              </div>
            </div>
            <div class="buttonContainer">
              <v-btn
                v-if="isEdit"
                :loading="loading"
                type="submit"
                color="indigo-darken-3"
                class="submit-button"
                >Submit <i class="fa-solid fa-paper-plane pl-2"></i
              ></v-btn>
              <v-btn v-if="!isEdit" color="indigo-darken-3" @click="handleNext">
                Next
                <i class="fa-solid fa-circle-right pl-2"></i>
              </v-btn>
            </div>
          </v-form>
        </div>
      </v-card>
    </div>

    <v-card class="maincontainer pa-3" v-if="!isProcessing" height="600" >
        <v-btn class="start-button" color="indigo-darken-3"  @click="handleNext">
        Start Queue
        <i class="fa-solid fa-play pl-2"></i>
        </v-btn>
    </v-card>

  </v-container>
</template>

<script>
import config from "../utils.js";
import axios from "axios";
import moment from "moment";

export default {
  data() {
    return {
      list: null,
      userData: {},
      selectedValue: "",
      loading: false,
      isEdit: false,
      isProcessing:false
    };
  },
  computed: {
    formatedInputs() {
      const inputs = this.userData.inputs;

      const groupedItems = {};

      for (const item of inputs) {
        const type = item.type;
        if (!groupedItems[type]) {
          groupedItems[type] = [];
        }
        groupedItems[type].push(item);
      }
      return groupedItems;
    },
    fomatDate() {
      const date = moment(this.userData.date);

      return date.format("dddd DD YYYY");
    },
  },
  methods: {
    download() {
      var token = localStorage.getItem("token");
      axios({
        method: "post",
        url: `${config.baseUrl}/appointment/download?code=${this.userData.code}`,
        headers: {
          Authorization: `Bearer ${token}`,
        },
        responseType: "blob", // Set responseType to 'blob' to receive binary data
      })
        .then((response) => {
          const contentDisposition = response.headers["content-disposition"];
          const fileNameMatch =
            contentDisposition && contentDisposition.match(/filename="(.+)"/);
          const fileName = fileNameMatch ? fileNameMatch[1] : "file.docx";

          const blob = new Blob([response.data]);
          const url = window.URL.createObjectURL(blob);
          const link = document.createElement("a");
          link.href = url;
          link.setAttribute("download", fileName);
          document.body.appendChild(link);
          link.click();
          document.body.removeChild(link);
          window.URL.revokeObjectURL(url);
        })
        .catch((error) => {
          console.error("Error downloading file:", error);
        });
    },

    handleNext() {
      var token = localStorage.getItem("token");
      axios({
        method: "post",
        url: `${config.baseUrl}/appointment/process`,
        headers: {
          Authorization: `Bearer ${token}`,
        },
        data: {
          appointment_id: this.userData.appointment_id,
        },
      })
        .then((res) => {
            this.getList();
        })
        .catch((error) => {
          console.error("Error occurred:", error);
        });
    },

    handleSubmit(values) {
      var token = localStorage.getItem("token");
      const inputs = values.target;
      const processData = [];
      for (const item of inputs) {
        if (item.type != "radio" && item.type != "submit") {
          console.log(item);
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
        url: `${config.baseUrl}/appointment/editAppointment`,
        headers: {
          Authorization: `Bearer ${token}`,
        },
        data: {
          date: this.userData.date,
          email: this.userData.email,
          values: JSON.stringify(processData),
          appointment_id: this.userData.appointment_id,
        },
      })
        .then((res) => {
          console.log(res);
        })
        .catch((error) => {
          console.error("Error occurred:", error);
        });
    },
    getList() {
      var token = localStorage.getItem("token");
      axios({
        method: "get",
        url: `${config.baseUrl}/appointment/queueList`,
        headers: {
          Authorization: `Bearer ${token}`,
        },
      })
        .then((res) => {
          const data = res.data.appointment;
          this.list = res.data.appointment;
          const processingData = {};
          const noQueue = 0;

          for (let i = 0; i < data.length; i++) {
            const item = data[i];
            if (item.status_id === 3) {
              console.log(item);
              const itemData = {
                appointment_id: item.appointment_id,
                date: item.date,
                code: item.code,
                email: item.email,
                statusId: item.status,
                inputs: JSON.parse(item.values),
              };

              this.userData = itemData;
            }
          }

          const hasStatusThree = this.list.some(item => item.status_id === 3);
            this.isProcessing = hasStatusThree;
        })
        .catch((error) => {
          console.error("Error occurred:", error);
        });
    },
  },
  mounted() {
    this.getList();
  },
};
</script>

<style lang="scss" scoped>
.buttonContainer {
  display: flex;
  gap: 10px;
  width: 20%;
  margin-left: auto;
  justify-content: end;
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
    color: #beb6b6;
  }
}
.content {
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 10px;
  i {
    color: #66bb6a;
  }

  .icon {
    i {
      font-size: 1rem;
    }
  }
  .spinning {
    animation: rotateSpinner 1s linear infinite; /* Adjust animation duration as needed */
  }

  @keyframes rotateSpinner {
    from {
      transform: rotate(0deg);
    }
    to {
      transform: rotate(360deg);
    }
  }
}

.title-container {
  display: flex;
  justify-content: space-between;
  align-items: center;

  .title-text {
    h2 {
      font-size: 1.2rem;
    }
    p {
      font-size: 0.8rem;
      color: #999999;
      font-weight: 500;
    }
  }

  .options {
    display: flex;
    gap: 10px;
  }
}
.start-button {
    width: 200px;
    min-height: 60px;
}

.maincontainer {
    display: flex;
    justify-content: center;
    align-items: center;
}
</style>
