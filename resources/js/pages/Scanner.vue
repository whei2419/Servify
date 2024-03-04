
<template>
  <v-container>
    <div class="header">
        <v-btn class="mb-3 primary-btn"  @click="goto()">
            <i class="fa-solid fa-left-long"></i>
                  <span class="ml-1">Back</span>
                </v-btn>
    </div>
    <v-card class="text-center pa-4 h-screen">
      <div v-if="error" class="error">{{ error }}</div>
      <div v-if="cameraNotDetected" class="error">Camera not detected</div>
      <div v-if="!error">
        <v-tabs v-model="tab" bg-color="transparent" color="basil" grow>
          <v-tab v-for="item in items" :key="item" :value="item">
            {{ item }}
          </v-tab>
        </v-tabs>
        <div v-if="message" class="message">
            <p class="pa-1 my-3 rounded-sm bg-warning "><small><i class="fa-solid fa-circle-info pr-2"></i> {{ message }}</small></p>
        </div>
        <v-window v-model="tab">
          <v-window-item v-for="item in items" :key="item" :value="item">
            <v-card flat class="pa-5">
              <div v-if="item === 'Scan'" class="scanner-container">
                <v-btn v-if="isScan === false" color="indigo-darken-3" size="large" @click="isScan = true">
                  <i class="fa-solid fa-qrcode"></i>
                  <span class="ml-1">Scan</span>
                </v-btn>
                <qrcode-stream
                    v-if="isScan"
                  @decode="onDecode"
                  :locate="true"
                ></qrcode-stream>
              </div>
              <div v-else>
                <v-form @submit.prevent="handleSubmit">
                  <v-text-field
                    v-model="qrCode"
                    :rules="rules"
                    label="Qr code"
                  ></v-text-field>
                  <v-btn
                    type="submit"
                    block
                    color="indigo-darken-3"
                    size="x-large"
                    variant="flat"
                    class="mt-2"
                    >Submit</v-btn
                  >
                </v-form>
              </div>
            </v-card>
          </v-window-item>
        </v-window>
      </div>
    </v-card>
  </v-container>
</template>

<script>
import axios from "axios";
import config from "../utils.js";
import {
  QrcodeStream,
  QrcodeDropZone,
  QrcodeCapture,
} from "vue3-qrcode-reader";
export default {
  name: "QRScanner",
  components: {
    QrcodeStream,
    QrcodeDropZone,
    QrcodeCapture,
  },
  data() {
    return {
        message:'',
        isScan:false,
      error: null,
      cameraNotDetected: false,
      tab: "Scan",
      items: ["Scan", "Manual"],
      qrCode: null,
      rules: [
        (value) => {
          if (value) return true;

          return "You must enter a Qrcode.";
        },
      ],
    };
  },
  methods: {
    goto(){
        this.$router.push({ name: "Dashboard" });
    },
    addQueue(value) {
      var token = localStorage.getItem("token");
      axios({
        method: "post",
        url: `${config.baseUrl}/appointment/addQueue`,
        headers: {
          Authorization: `Bearer ${token}`,
        },
        data: {
          code: value,
        },
      })
        .then((res) => {
            this.message = `Alert : ${res.data.status}`;
            setTimeout(() => {
              this.message  = '';
           }, 3000);
            this.isScan = false;
        })
        .catch((error) => {
          console.error("Error occurred:", error);
        });
    },
    handleSubmit() {
      this.addQueue(this.qrCode);
    },
    onDecode(decodedString) {
      this.addQueue(decodedString);
    },
    onError(error) {
      console.error("Error while scanning QR code:", error);
      this.error = "Error while scanning QR code: " + error;
    },
    onNotFound() {
      this.error = "No camera detected";
      this.cameraNotDetected = true;
    },
  },
  mounted() {
    if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
      this.error = "getUserMedia is not supported by your browser";
      return;
    }
    // If getUserMedia is supported, check for camera devices
    navigator.mediaDevices
      .enumerateDevices()
      .then((devices) => {
        const cameras = devices.filter(
          (device) => device.kind === "videoinput"
        );
        if (cameras.length === 0) {
          this.onNotFound();
        }
      })
      .catch((error) => {
        console.error("Error enumerating devices:", error);
        this.onError("Error enumerating devices: " + error);
      });
  },
};
</script>

<style>
.error {
  color: red;
}
.scanned-data {
  margin-top: 20px;
}
.scanner-container {
    display: flex;
    justify-content: center;
    align-items: center;
    height: 500px;
}
</style>
