<template>
    <div class="component p-4">
        <label for="modus">Modus wählen: </label>
        <select id="modus" v-model="modus">
            <option value="">bitte wählen</option>
            <option value="jobcenter">Jobcenter</option>
            <option value="arbeitsagentur">Arbeitsagentur</option>
        </select>
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
