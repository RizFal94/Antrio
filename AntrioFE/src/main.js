import './assets/main.css'

import { createApp } from 'vue'
import { createPinia } from 'pinia'

import App from './App.vue'
import router from './router'
import 'bootstrap-icons/font/bootstrap-icons.css'
// import axios from 'axios'

// axios.defaults.withCredentials = true
// axios.defaults.baseURL = import.meta.env.VITE_API_BASE_URL

const app = createApp(App)

app.use(createPinia())
app.use(router)

app.mount('#app')
