import { createApp } from "vue"; 
import App from "/src/App.vue"; 
import vuetify from "/src/plugins/vuetify.js"; 
import { loadFonts } from "/src/plugins/webfontloader.js"; 

// Load fonts
loadFonts();

// Create the Vue app
const app = createApp(App);

app.config.globalProperties.$ApiAddress = import.meta.env.VITE_APP_BASE_API_URL;
// Use Vuetify
app.use(vuetify);

// Mount the app
app.mount("#app");