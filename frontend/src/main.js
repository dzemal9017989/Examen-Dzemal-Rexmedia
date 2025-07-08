// This imports axios for working between backend and front end
import axios from './axios'
window.axios = axios

// This imports bootstrap
import 'bootstrap/dist/css/bootstrap.min.css'
import './assets/main.css'
import { createApp } from 'vue'
// This imports pinia
import { createPinia } from 'pinia'

import App from './App.vue'
import router from './router'

const app = createApp(App)

app.use(createPinia())
app.use(router)

app.mount('#app')



