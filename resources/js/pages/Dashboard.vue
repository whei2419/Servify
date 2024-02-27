<template>
  <v-card class="main">
    <v-layout>
      <v-navigation-drawer
        v-model="drawer"
        :rail="rail"
        permanent
        @click="rail = false"
      >
        <v-list-item
          prepend-avatar="images/logo.png"
          class="pa-1 pl-3 my-2 ml-1"
          nav
        >
          <template v-slot:append>
            <v-btn variant="text" @click.stop="rail = !rail">
              <i class="fa-solid fa-bars"></i>
            </v-btn>
          </template>
        </v-list-item>
        <v-divider></v-divider>
        <v-list class="main-nav" density="compact" nav>
          <v-list-item
            @click="selectedItem = 'dashboard'"
            title="Dashboard"
            value="dashboard"
          >
            <template v-slot:prepend>
              <div class="icon">
                <i class="fa-solid fa-table-columns"></i>
              </div>
            </template>
          </v-list-item>
          <v-list-item
            @click="selectedItem = 'queue'"
            title="Processing"
            value="queue"
          >
            <template v-slot:prepend>
              <div class="icon">
                <i class="fa-solid fa-rectangle-list"></i>
              </div>
            </template>
          </v-list-item>
          <v-list-item
            @click="selectedItem = 'queuelist'"
            title="Queue list"
            value="queuelist"
          >
            <template v-slot:prepend>
              <div class="icon">
                <i class="fa-regular fa-rectangle-list"></i>
              </div>
            </template>
          </v-list-item>
          <v-list-item
            @click="selectedItem = 'apointment'"
            title="Appointments"
            value="apointment"
          >
            <template v-slot:prepend>
              <div class="icon">
                <i class="fa-solid fa-address-book"></i>
              </div>
            </template>
          </v-list-item>
          <v-list-item
            :to="{ name: 'Scanner' }"
            title="Scanner"
            value="scanner"
          >
            <template v-slot:prepend>
              <div class="icon">
                <i class="fa-solid fa-qrcode"></i>
              </div>
            </template>
          </v-list-item>
          <v-list-item class="logout-item" title="Logout" @click="dialog = true">
            <template v-slot:prepend>
              <div class="icon">
                <i class="fa-solid fa-right-from-bracket"></i>
              </div>
            </template>
          </v-list-item>
        </v-list>
      </v-navigation-drawer>
      <v-main>
        <div class="h-screen" v-if="selectedItem === 'queue'">
          <Processing></Processing>
        </div>
        <div class="h-screen" v-else-if="selectedItem === 'queuelist'">
          <div class="dashboard-container">
            <div class="header-queue">
                <h1 class="mb-4 title">Queue list</h1>
                <button class="full-screen-icon" @click="goto()">
                    <i class="fa-solid fa-expand"></i>
                </button>
            </div>

            <QueueList></QueueList>
          </div>
        </div>
        <div v-else-if="selectedItem === 'dashboard'">
          <DashBoard></DashBoard>
        </div>
        <div class="h-screen pt-10" v-else>
          <TableContoller></TableContoller>
        </div>
      </v-main>
    </v-layout>
    <v-dialog
      v-model="dialog"
      width="auto"
    >
      <v-card
        width="400"
        class="logout-dialog"
      >
      <div class="header mb-3">
        <h3>Logout</h3>
        <p class="text-center mt-10">Are you sure you want to logout?</p>
      </div>
        <div class="actions">
            <v-btn
            class="ms-auto danger-btn mr-2"
            text="Cancel"
            @click="dialog = false"
          ></v-btn>
          <v-btn
            class="ms-auto primary-btn"
            text="Confirm"
            @click="handleLogout"
          ></v-btn>
        </div>
      </v-card>
    </v-dialog>
  </v-card>
</template>

<script>
import TableContoller from "../components/TableContoller.vue";
import QueueList from "../components/Queue.vue";
import Processing from "../components/Processing.vue";
import axios from "axios";
import config from "../utils.js";
import DashBoard from "../components/DashBoard.vue";
export default {
  components: {
    TableContoller,
    QueueList,
    Processing,
    DashBoard,
  },
  data() {
    return {
      drawer: true,
      rail: false,
      selectedItem: "dashboard",
      dialog:false
    };
  },
  methods: {
    goto(){
        this.$router.push({ name: "Quelist" });
    },
    handleLogout() {
      var token = localStorage.getItem("token");
      axios({
        method: "get",
        url: `${config.baseUrl}/logout`,
        headers: {
          Authorization: `Bearer ${token}`,
        },
      })
        .then((res) => {
          console.log(token);
          this.$router.push({ name: "Login" });
          localStorage.removeItem("token");
        })
        .catch((error) => {
          console.error("Error occurred:", error);
        });
    },
  },
};
</script>

<style lang="scss" scoped>
.v-list-item {
  padding: 0 !important;
  gap: 5px;
  padding: 5px !important;
  .v-list-item__prepend {
    .v-img__img {
      width: 30px !important;
      height: 30px !important;
    }
    img {
      width: 30px !important;
      height: 30px !important;
    }
  }
}
.v-img__img {
  width: 30px !important;
  height: 30px !important;
}
.v-navigation-drawer {
  box-shadow: rgba(99, 99, 99, 0.2) 0px 2px 8px 0px;
  border: solid #fff 1px;
}

.header-queue {
    display: flex;
    justify-content: space-between;
    align-items: center;

    button {
        padding: 5px 20px;
        border-radius: 4px;
        background: #FF9F2F;
        box-shadow: rgba(0, 0, 0, 0.24) 0px 3px 8px;
        color: #fff;

    }
}

.main-nav {
    position: relative;
    height: 91%;
}
.logout-item {
    position: absolute;
    z-index: 99;
    bottom: 0;
    left: 8;

}
.logout-dialog {
    border-radius: 8px !important;
    padding: 10px 20px 20px 20px;
    height: 200px;

    .actions {
        margin-top: auto;
        margin-left: auto;
        align-items: end;
    }
}
</style>
