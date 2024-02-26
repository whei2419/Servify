<template>
    <div class="dashboard-container">
        <h1 class="mb-4 title">Queue list</h1>
        <v-card flat class=" main-card">
    <v-text-field
      v-model="search"
      @change="getList"
      label="Search Qr code"
      outlined
    ></v-text-field>
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
    >
      <template v-slot:item.actions="{ item }">
        <div class="w-100 d-flex ga-3">
          <v-dialog width="800">
            <template v-slot:activator="{ props }">
              <v-btn size="sm" elevation="0" v-bind="props"> <i class="fa-solid fa-eye"></i> </v-btn>
            </template>

            <template v-slot:default="{ isActive }">
              <v-card :title="item.document.name">
                <v-card-text>
                  <div class="d-inline-block mr-3 border px-5 py-2 rounded-sm" v-for="(item,index) in buildJson(item.values)" :key="index">
                    <p> <span>{{ item.name }}: </span> {{ item.value }}</p>
                  </div>
                </v-card-text>
                <v-card-actions>
                  <v-spacer></v-spacer>

                  <v-btn
                    text="Close"
                    @click="isActive.value = false"
                  ></v-btn>
                </v-card-actions>
              </v-card>
            </template>
          </v-dialog>
          <v-btn size="sm" elevation="0">
            <i class="fa-solid fa-trash"></i>
          </v-btn>
          <v-btn size="sm" elevation="0">
            <i class="fa-solid fa-pen-to-square"></i>
          </v-btn>
        </div>
      </template>
    </v-data-table-server>
  </v-card>
    </div>

</template>

  <script>
import axios from "axios";
import config from "../utils.js";
export default {
  data() {
    return {
      loading: true,
      itemsPerPage: 10,
      totalItems: 0,

      search: "",
      headers: [
        {
          key: "id",
          sortable: false,
          title: "Id",
        },
        { key: "code", title: "Qr code" },
        { key: "date", title: "Date" },
        { key: "status.name", title: "Status" },
        { key: "email", title: "Email" },
        { key: "document.name", title: "Document name" },
        { key: "actions", title: "Actions" },
      ],
      items: [],
    };
  },
  methods: {
    buildJson(data){
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
          code:this.search

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
  mounted() {},
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
.main-card{
    border-radius: 8px !important;
    box-shadow: rgba(149, 157, 165, 0.2) 0px 8px 24px !important;
    padding: 20px  !important;
}
</style>
