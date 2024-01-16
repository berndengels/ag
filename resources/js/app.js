import './bootstrap';
import {createApp} from "vue"
import CrawlerView from "v@/views/CrawlerView.vue";

$(document).ready(() => {
	const app= document.getElementById('app');
	createApp(CrawlerView).mount(app);
});
