<template>
    <div class="dashboard-container">
        <h1 class="mb-4 title">Queue list</h1>
      <div v-for="(item, index) in list" :key="index" class="queue">
      <v-card loading   v-if="item.status_id === 3" class="mb-4">
        <div class="content pa-5">
          <div class="details">
            <p>
              <span><i class="fa-solid fa-list-ul"></i> </span> Queue :
              <span>{{ item.code }}</span>
            </p>
          </div>
          <div class="que-icon">
            <p>
              Processing..
              <span> <i class="fa-solid fa-spinner spinning"></i></span>
            </p>
          </div>
        </div>
      </v-card>
        <v-card v-if="item.status_id === 2" class="mb-4">
          <div class="content pa-5">
            <div class="details">
              <p>
                <span><i class="fa-solid fa-list-ul pr-1"></i> </span> Queue :
                <span>{{ item.code }}</span>
              </p>
            </div>
            <div class="que-icon">
              <p>
                Waiting...
                <span> <i class="fa-solid fa-hourglass-start"></i></span>
              </p>
            </div>
          </div>
        </v-card>
      </div>
    </div>
</template>

  <script>
  import Pusher from "pusher-js";
  import config from "../utils.js";
  import axios from "axios";

  export default {
    data() {
      return {
        list: null,
      };
    },
    methods: {
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
            this.list = res.data.appointment;
            console.log(this.list);
          })
          .catch((error) => {
            console.error("Error occurred:", error);
          });
      },
    },
    mounted() {
      this.getList();
      const pusher = new Pusher(config.pusherKey, {
        cluster: config.pusherCluster,
      });

      const channel = pusher.subscribe("queue");

      channel.bind("App\\Events\\QueueEvent", (data) => {
        console.log(data);
        this.list = data.appointment;
      });
    },
  };
  </script>

  <style lang="scss" scoped>
  .content {
    display: flex !important;
    align-items: center;
    justify-content: space-between;
    gap: 10px;
    padding: 20px 30px !important;
    i {
      color: #66bb6a;
    }

    .que-icon {
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
  </style>
