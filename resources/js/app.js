import { createApp } from 'vue';
import Index from "./Index.vue";
import router from './router/router';
import { createPinia } from "pinia"; // Corrected spelling of 'import'

// Vuetify
import 'vuetify/styles';
import { createVuetify } from 'vuetify';
import * as components from 'vuetify/components';
import * as directives from 'vuetify/directives';

const app = createApp(Index);
const vuetify = createVuetify({
  components,
  directives,
  theme: {
    defaultTheme: 'dark'
  }
});

app.use(createPinia());
app.use(router);
app.use(vuetify);
app.mount('#app');
