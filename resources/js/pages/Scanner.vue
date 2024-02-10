
<template>
  <v-container>
    <v-card class="text-center pa-4 h-screen">
      <div v-if="error" class="error">{{ error }}</div>
      <div v-if="cameraNotDetected" class="error">Camera not detected</div>
      <div v-if="!error">
        <v-tabs v-model="tab" bg-color="transparent" color="basil" grow>
          <v-tab v-for="item in items" :key="item" :value="item">
            {{ item }}
          </v-tab>
        </v-tabs>
        <v-window v-model="tab">
          <v-window-item v-for="item in items" :key="item" :value="item">
            <v-card flat class="pa-5">
              <qrcode-stream
                v-if="item === 'Scan'"
                @decode="onDecode"
                :locate="true"
              ></qrcode-stream>
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
    handleSubmit() {
      console.log("test");
    },
    onDecode(decodedString) {
      console.log(decodedString);
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
</style>
