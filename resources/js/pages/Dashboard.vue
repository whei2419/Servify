<template>
  <v-card>
    <v-layout>
      <v-navigation-drawer
        v-model="drawer"
        :rail="rail"
        permanent
        @click="rail = false"
      >
        <v-list-item
          prepend-avatar="https://randomuser.me/api/portraits/men/85.jpg"
          title="Employee"
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
        <v-list density="compact" nav>
          <v-list-item
            @click="selectedItem = 'queue'"
            title="Processing"
            value="queue"
          >
            <template v-slot:prepend>
              <i class="fa-solid fa-list-check pr-2"></i>
            </template>
          </v-list-item>
          <v-list-item
            @click="selectedItem = 'queuelist'"
            title="Queue list"
            value="queuelist"
          >
            <template v-slot:prepend>
              <i class="fa-solid fa-list-check pr-2"></i>
            </template>
          </v-list-item>
          <v-list-item :to="{ name: 'Quelist' }" title="Queue list full screen">
            <template v-slot:prepend>
              <i class="fa-solid fa-list-ul pr-2"></i>
            </template>
          </v-list-item>
          <v-list-item
            @click="selectedItem = 'apointment'"
            title="Appointment"
            value="apointment"
          >
            <template v-slot:prepend>
              <i class="fa-solid fa-address-book pr-2"></i>
            </template>
          </v-list-item>
          <v-list-item
            :to="{ name: 'Scanner' }"
            title="Scanner"
            value="scanner"
          >
            <template v-slot:prepend>
              <i class="fa-solid fa-qrcode pr-2"></i>
            </template>
          </v-list-item>
          <v-list-item title="Loguot" @click="handleLogout">
            <template v-slot:prepend>
                <i class="fa-solid fa-right-from-bracket pr-2"></i>
            </template>
          </v-list-item>
        </v-list>
      </v-navigation-drawer>
      <v-main>
        <v-container class="h-screen" v-if="selectedItem === 'queue'">
          <Processing></Processing>
        </v-container>
        <v-container class="h-screen" v-else-if="selectedItem === 'queuelist'">
          <h2 class="ml-3 mt-10">Queue list</h2>
          <QueueList></QueueList>
        </v-container>
        <v-container class="h-screen pt-10" v-else>
          <TableContoller></TableContoller>
        </v-container>
      </v-main>
    </v-layout>
  </v-card>
</template>

<script>
import TableContoller from "../components/TableContoller.vue";
import QueueList from "../components/Queue.vue";
import Processing from "../components/Processing.vue";
import axios from "axios";
import config from "../utils.js";
export default {
  components: {
    TableContoller,
    QueueList,
    Processing,
  },
  data() {
    return {
      drawer: true,
      rail: false,
      selectedItem: "queue",
    };
  },
  methods: {
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
            localStorage.removeItem('token');
        })
        .catch((error) => {
          console.error("Error occurred:", error);
        });
    },
  },
};
</script>
