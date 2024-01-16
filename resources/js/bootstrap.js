import "@popperjs/core";
import 'bootstrap'
import jQuery from 'jquery';
import axios from 'axios';
window.jQuery = window.$ = jQuery;
window.axios = axios;
axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
axios.defaults.headers.common['Content-Type'] = 'application/json';
//axios.defaults.withCredentials = true;
