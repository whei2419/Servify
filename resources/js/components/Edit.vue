<template>
    <div >
      <div class="queue">
        <div  class="edit-card">
            <div class="title-container mb-3">
              <div class="title-text pr-3">
                <h2>Appointment date : {{ fomatDate }}</h2>
                <p class="subtitle">Code: {{ userData.code }}</p>
              </div>
            </div>
          <v-divider></v-divider>
          <div class="content ">
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

                      required
                    />
                  </div>
                </div>
              </div>
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
                    :data-name="input.name"
                    :value="input.value"
                    :checked="input.selected"
                    v-model="selectedValue"
                    @change="getSelectedValue"
                    required
                  />
                  <label class="ml-2">{{ input.name }}</label>
                </div>
              </div>
              <div class="buttonContainer">
                <v-btn
                  :loading="loading"
                  type="submit"

                  class="submit-button primary-btn"
                  >Submit</v-btn>
              </div>
            </v-form>
          </div>
        </div>
      </div>
    </div>
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
    props:{
        data:{
            type:Object
        }
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
        getSelectedValue(event) {
            this.selectedValue = event.target.value;
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
          } else if (item.type == "radio") {
            processData.push({
              id: item.value,
              value: item.value,
              type: item.type,
              name: item.dataset.name,
              selected: this.selectedValue == item.value ? true : false,
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
            this.$emit('reset');
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
    created(){
        const itemData = {
                  appointment_id: this.data.id,
                  date: this.data.date,
                  code: this.data.code,
                  email: this.data.email,
                  statusId: this.data.status,
                  inputs: JSON.parse(this.data.values),
                };
        this.userData = itemData;
    }

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
    grid-template-columns: repeat(4, 1fr); /* Two columns with equal width */
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
  .main-card{
      border-radius: 8px !important;
      box-shadow: rgba(149, 157, 165, 0.2) 0px 8px 24px;
  }
  .edit-card {
    padding: 0 !important;
  }
  </style>
