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
        <Crawler v-if="modus" :modus="modus"/>
    </div>
</template>

<script>
import Crawler from "../components/Crawler.vue";

export default {
    name: "CrawlerView",
    data() {
        return {
            modus: null,
        }
    },
    components: {Crawler},
}
</script>

<style scoped>
</style>
