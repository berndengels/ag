<template>
    <div class="container mt-3" v-show="locations.length > 0">
        <h3>erledigt: {{ counter }}</h3>
        <h3>Anzahl Postleitzahlen: {{ count }}</h3>
        <h3>abgearbeitete Postleitzahlen: {{ counter + 1 }}</h3>
        <div class="mt-3">
            <h3 v-if="currentLocation">Suche für Kunden in: {{ currentLocation.zipcode }} {{ currentLocation.name }}</h3>
            <h3 v-if="info" v-html="info"></h3>
            <p v-if="error">Keine Daten gefunden</p>
            <div class="container" v-if="entity">
                <span class="font-bold" v-html="entity.name"></span>
                <span class="ml-2" v-html="entity.street + ', ' + entity.postcode + ' ' + entity.city"></span>
            </div>
            <div v-if="image" class="container justify-center justify-items-center">
                <img class="self-center" height="200" :src="image" :alt="image" />
            </div>
        </div>
        <div v-if="url" class="mt-3">
            <span>Such-URL: {{ url }}</span>
        </div>
        <div class="mt-3">
            <button v-if="!running" class="btn btn-green rounded" @click="crawle">Start Import</button>
            <button v-if="running" class="btn btn-red rounded" @click="stop">Stop Import</button>
        </div>
    </div>
</template>

<script>
import axios from "axios";

const url = 'https://ag.test/api/',
    minDelay = 1000,
    maxDelay = 2000;

export default {
    name: "Crawler",
    props: ['modus'],
    data() {
        return {
            counter: 0,
            locations: [],
            currentLocation: null,
            count: 0,
            entity: null,
            error: false,
            info: null,
            image: null,
            url: null,
            intVal: null,
            running: false
        }
    },
    mounted() {
        this.getLocations();
        console.info('modus', this.modus)
    },
    methods: {
        getRandomInt(min, max) {
            min = Math.ceil(min);
            max = Math.floor(max);
            return Math.floor(Math.random() * (max - min) + min); // The maximum is exclusive and the minimum is inclusive
        },
        getLocations() {
            axios.get(url + this.modus + "/locations")
                .then(resp => {
                    this.locations = resp.data.locations;
                    this.count = resp.data.locations.length;
                    this.currentLocation = this.locations[this.counter];
                })
                .catch(err => console.error(err));
        },
        crawle() {
            let delay = this.getRandomInt(minDelay, maxDelay);
            this.intVal = setInterval(() => {
                try {
                    if(this.counter >= this.count - 1) {
                        this.info = "Suche beendet";
                        this.running = false;
                        clearInterval(this.intVal);
                        return;
                    }
                    this.running = true;
                    this.fetch();
                } catch(err) {
                    console.error(err);
                    this.info = "Suche beendet";
                    this.running = false;
                    clearInterval(this.intVal);
                }
            }, delay)
        },
        stop(){
            clearInterval(this.intVal);
        },
        fetch() {
            this.currentLocation = this.locations[this.counter] ?? null;
            if(this.currentLocation) {
                axios.get(url + this.modus + "/crawle/" + this.currentLocation.zipcode + "/" + this.currentLocation.name)
                    .then(resp => {
                        if (resp.data.error) {
                            this.entity = null;
                            this.image = null;
                            this.url = null;
                            this.error = true;
                        } else if (resp.data.entity) {
                            this.url = resp.data.url;
                            this.entity = resp.data.entity;
                            this.image = resp.data.image;
                            this.error = false;
                        }
                        this.counter++;
                    })
                    .catch(err => console.error(err));
            }
        }
    }
}
</script>

<style scoped>
</style>
