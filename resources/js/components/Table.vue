<template>
  <div class="dashboard-container">
    <h1 class="mb-4 title">Appointments</h1>
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
        </template>

        <template v-slot:item.actions="{ item }">
          <div class="w-100 d-flex ga-3">
            <v-dialog width="800">
              <template v-slot:activator="{ props }">
                <v-btn size="sm" elevation="0" v-bind="props">
                  <i class="fa-solid fa-eye"></i>
                </v-btn>
              </template>

              <template v-slot:default="{ isActive }">
                <v-card class="view-form">
                  <v-card-text>
                    <div class="modal-title">
                      <div class="title">
                        <h2>{{ item.document.name }}</h2>
                        <p>Qr code:{{ item.code }}</p>
                      </div>
                      <button class="close-btn" @click="isActive.value = false">
                        <i class="fa-solid fa-xmark"></i>
                      </button>
                    </div>
                    <div class="form-border">
                      <div
                        class="d-inline-block mr-3 border px-5 py-2 mb-2 rounded-sm"
                        v-for="(item, index) in buildJson(item.values)"
                        :key="index"
                      >
                        <p>
                          <span>{{ item.name }}: </span> {{ item.value }}
                        </p>
                      </div>
                    </div>
                  </v-card-text>
                </v-card>
              </template>
            </v-dialog>


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
                        <h2>{{ item.document.name }}</h2>
                        <p>Qr code:{{ item.code }}</p>
                      </div>
                      <button class="close-btn" @click="isActive.value = false">
                        <i class="fa-solid fa-xmark"></i>
                      </button>
                    </div>
                    <div class="form-border">
                      <div
                        class="d-inline-block mr-3 border px-5 py-2 mb-2 rounded-sm"
                        v-for="(item, index) in buildJson(item.values)"
                        :key="index"
                      >
                        <p>
                          <span>{{ item.name }}: </span> {{ item.value }}
                        </p>
                      </div>
                    </div>
                  </v-card-text>
                </v-card>

              </template>
            </v-dialog>
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
        { key: "code", title: "Qr code" },
        { key: "date", title: "Date" },
        { key: "name", title: "Name" },
        { key: "status", title: "Status" },
        { key: "email", title: "Email" },
        { key: "document.name", title: "Document type" },
        { key: "actions", title: "" },
      ],
      items: [],
    };
  },
  methods: {
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
  }
}
th {
    font-weight: 600 !important;
}
</style>
