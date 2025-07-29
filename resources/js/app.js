import { createApp } from 'vue'
import App from './App.vue'
import router from './router'
import store from './store'
import vuetify from './plugins/vuetify'
import '@fortawesome/fontawesome-free/css/all.min.css';

createApp(App)
    .use(router)
    .use(vuetify)
    .use(store)
    .mount('#app')