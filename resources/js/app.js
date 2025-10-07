import './bootstrap';
import { createApp } from "vue"
import ScraperView from "v@/views/ScraperView.vue";

$(document).ready(() => {
	const app= document.getElementById('app');
	createApp(ScraperView).mount(app);
});
