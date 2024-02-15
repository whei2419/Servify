<template>
    <v-card
    flat
      class="pa-3 rounded-sm"
    >
      <template v-slot:text>
        <div class="header">

        <v-text-field
          v-model="search"
          label="Search"
          single-line
          variant="outlined"
          hide-details
          class="search"
        >
        </v-text-field>
        </div>

      </template>

      <v-data-table
        class="border"
        :headers="headers"
        :items="items"
        :search="search"
      ></v-data-table>
    </v-card>
</template>

  <script>
  import axios from "axios";
import config from "../utils.js";
    export default {
      data () {
        return {
          search: '',
          headers: [
            {

              key: 'id',
              sortable: false,
              title: 'Id',
            },
            { key: 'code', title: 'Qr code' },
            { key: 'email', title: 'Email' },
          ],
        items:[]
          ,
        }
      },
      methods: {
        getList() {
      var token = localStorage.getItem("token");
      axios({
        method: "post",
        url: `${config.baseUrl}/appointment/appointmentList`,
        headers: {
          Authorization: `Bearer ${token}`,
        },
        data : {
            limit:10,
        }
      })
        .then((res) => {
          this.items = res.data.data;
        })
        .catch((error) => {
          console.error("Error occurred:", error);
        });
    },
      },
      mounted() {
        this.getList();
      }
    }
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
</style>
