<template>
  <v-container class="w-100 h-screen main-container">
    <Navbar></Navbar>
    <div class="container first-container">
      <v-img
        class="mx-auto mb-10"
        max-width="250"
        aspect-ratio="16/9"
        cover
        src="images/documents.jpg"
      ></v-img>
      <p class="text-center mb-4">
        Dont know what requirements needed for your certificate? Search below to show the list of requirements.
      </p>
      <v-select
        v-model="documentType"
        label="Choose Document type*"
        :item-props="itemProps"
        :items="documentsList"
        class="document-type"
      ></v-select>
      <div v-if="documentType" class="details">
        <p class="text-bold">Requirements</p>
        <ul  class="ml-10">
          <li v-for="item in this.documentType.requirement"
             :key="item.id">{{ item.name }}</li>
        </ul>
        <!-- <p v-if="this.documentType.requirement.length > 1 && documentType " class="text-danger tex-center">No requirement needed</p> -->
      </div>
    </div>
  </v-container>
</template>

<script>
import Navbar from "../components/Navbar.vue";
import axios from "axios";
import config from "../utils";
export default {
  components: {
    Navbar,
  },
  data() {
    return {
      documentsList: [],
      documentType: null,
    };
  },
  created() {
    this.getDocuments();
  },
  methods: {
    itemProps(item) {
      return {
        title: item.name,
      };
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
  },
};
</script>

<style lang="scss" scoped>
.main-container {
  background: #fff;
}
.first-container {
  max-width: 600px;
  margin-top: 60px;
}
.text-bold {
  font-weight: 600;
}
</style>
