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
              <v-btn
                variant="text"
                @click.stop="rail = !rail"
              >
              <i class="fa-solid fa-bars"></i>
              </v-btn>
            </template>
          </v-list-item>
          <v-divider></v-divider>
          <v-list density="compact" nav>
            <v-list-item @click="selectedItem = 'queue'"  title="Queue" value="queue">
                <template v-slot:prepend>
                    <i class="fa-solid fa-list-check pr-2"></i>
                </template>
            </v-list-item>
            <v-list-item :to="{ name: 'Quelist' }"  title="Queue list" value="queuelist">
                <template v-slot:prepend>
                    <i class="fa-solid fa-list-ul pr-2"></i>
                </template>
            </v-list-item>
            <v-list-item  @click="selectedItem = 'apointment'"  title="Appointment" value="apointment">
                <template v-slot:prepend>
                    <i class="fa-solid fa-address-book pr-2"></i>
                </template>
            </v-list-item>
            <v-list-item :to="{ name: 'Scanner' }" title="Scanner" value="scanner">
                <template v-slot:prepend>
                    <i class="fa-solid fa-qrcode pr-2"></i>
                </template>
            </v-list-item>
          </v-list>
        </v-navigation-drawer>
        <v-main>
            <v-container class="h-screen"  v-if="selectedItem === 'queue'">
                test
            </v-container>
            <v-container class="h-screen pt-10" v-else>
                <TableContoller></TableContoller>
            </v-container>
        </v-main>
      </v-layout>
    </v-card>
</template>

<script>
import TableContoller from '../components/TableContoller.vue';
    export default {
        components:{
            TableContoller
        },
      data () {
        return {
          drawer: true,
          rail: false,
          selectedItem:'queue',
        }
      },
    }
  </script>
