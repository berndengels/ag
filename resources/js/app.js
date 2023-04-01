import './bootstrap';

import Alpine from 'alpinejs';
import '@fortawesome/fontawesome-free/scss/fontawesome.scss';
import '@fortawesome/fontawesome-free/scss/brands.scss';
import '@fortawesome/fontawesome-free/scss/regular.scss';
import '@fortawesome/fontawesome-free/scss/solid.scss';
import '@fortawesome/fontawesome-free/scss/v4-shims.scss';
import 'toastr/toastr';
import jQuery from 'jquery';
import axios from 'axios';
import { createApp } from "vue"
import CrawlerView from "@v/views/CrawlerView.vue";

window.Alpine = Alpine;
window.$ = jQuery;
window.axios = axios;
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
//window.axios.defaults.baseURL = process.env.MIX_API_URL;
//	axios.defaults.withCredentials = false;

Alpine.start();
var app = null;
switch(true) {
    case $("#crawler").is(":visible"):
        app = createApp(CrawlerView);
        app.mount("#crawler")
        break;
}
