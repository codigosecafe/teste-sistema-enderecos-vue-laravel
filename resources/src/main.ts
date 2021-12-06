import './bootstrap';
import { createApp } from "vue";
import App from "./components/app/App.vue";
import axios from 'axios'
import VueMask from 'vue-jquery-mask';
import VueSweetalert2 from 'vue-sweetalert2';
import 'sweetalert2/dist/sweetalert2.min.css';



import mitt from 'mitt';

const emitter = mitt();

const app = createApp(App);
app.config.globalProperties.$http = axios;
app.config.globalProperties.emitter = emitter;
app.use(VueMask);
app.use(VueSweetalert2);

app.mount("#app");
export default {
    install(app: { config: { globalProperties: { $validate: (data: object, rule: object) => void; }; }; }, options: any) {
      app.config.globalProperties.$validate = (data: object, rule: object) => {
        // check whether the object meets certain rules
      }
    }
  }
