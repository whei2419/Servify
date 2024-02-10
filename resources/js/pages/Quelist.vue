<template>
  <v-container>
    <v-card class="mb-4">
      <div class="content pa-5">
        <div class="details">
          <p>
            <span><i class="fa-solid fa-list-ul"></i> </span> Queue :
            <span>1</span>
          </p>
        </div>
        <div class="icon">
          <p>
            Processing..
            <span>  <i class="fa-solid fa-spinner spinning"></i></span>
          </p>
        </div>
      </div>
    </v-card>
    <v-divider class="mb-4"></v-divider>
    <v-card class="mb-4">
      <div class="content pa-5">
        <div class="details">
          <p>
            <span><i class="fa-solid fa-list-ul"></i> </span> Queue :
            <span>1</span>
          </p>
        </div>
        <div class="icon">
          <p>
            Waiting...
            <span>  <i class="fa-solid fa-hourglass-start"></i></span>
          </p>
        </div>
      </div>
    </v-card>
  </v-container>
</template>

<script>
export default {
  data() {
    return {};
  },
};
</script>

<style lang="scss" scoped>
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
</style>
