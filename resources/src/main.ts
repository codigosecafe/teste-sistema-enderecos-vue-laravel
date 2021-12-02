require("./bootstrap");
import { createApp } from "vue";
import App from "./components/app/App.vue";
import axios from 'axios'
const app = createApp(App);
app.config.globalProperties.$http = axios;
export default {
  install(app: { config: { globalProperties: { $validate: (data: object, rule: object) => void; }; }; }, options: any) {
    app.config.globalProperties.$validate = (data: object, rule: object) => {
      // check whether the object meets certain rules
    }
  }
}
app.mount("#app");
