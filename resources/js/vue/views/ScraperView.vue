<template>
    <div class="component p-4">
		<form class="form-floating w-25">
			<select id="modus" v-model="modus" class="form-control d-inline-block ms-3">
				<option value="">Modus wählen ...</option>
				<option value="jobcenter">Jobcenter</option>
				<option value="arbeitsagentur">Arbeitsagentur</option>
			</select>
		</form>
        <br>
        <Scraper v-if="modus" :modus="modus"/>
    </div>
</template>

<script>
import Scraper from "../components/Scraper.vue";

export default {
    name: "CrawlerView",
    data() {
        return {
            modus: null,
        }
    },
    components: { Scraper },
}
</script>

<style scoped>
</style>
