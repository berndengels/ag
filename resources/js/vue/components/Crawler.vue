<template>
    <div class="container mt-3" v-show="locations.length > 0">
        <h3>erledigt: {{ counter }}</h3>
        <h3>noch verbleibende Postleitzahlen: {{ count }}</h3>
        <div class="mt-3">
            <h3 v-if="currentLocation">Suche für Kunden in: {{ currentLocation.zipcode }} {{ currentLocation.place }}</h3>
            <h3 v-if="info" v-html="info"></h3>
            <p v-if="error">Keine Daten gefunden</p>
            <div class="container" v-if="entity">
                <span class="font-bold" v-html="entity.name"></span>,
                <span class="ml-2" v-html="entity.street + ', ' + entity.postcode + ' ' + entity.city"></span>
            </div>
            <div v-if="image" class="container justify-center justify-items-center">
                <img class="self-center" height="200" :src="image" :alt="image" />
            </div>
        </div>
        <div class="mt-3">
            <button v-if="!running" class="btn btn-green rounded" @click="crawle">Start Import</button>
            <button v-if="running" class="btn btn-red rounded" @click="stop">Stop Import</button>
        </div>
    </div>
</template>

<script>
import axios from "axios";

const url = 'https://ag.test/api/';
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
            let delay = this.getRandomInt(2000, 4000);
            this.running = true;
            this.intVal = setInterval(() => {
                try {
                    this.counter++;
                    this.currentLocation = this.locations[this.counter] ?? null;
                    if(this.currentLocation) {
                        this.fetch(this.currentLocation.zipcode);
                    }
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
        fetch(postcode) {
            axios.get(url + this.modus + "/crawle/" + postcode)
                .then(resp => {
                    if(resp.data.error) {
                        this.entity = null;
                        this.image = null;
                        this.error = true;
                    } else if(resp.data.entity) {
                        this.entity = resp.data.entity;
                        this.image = resp.data.image;
                        this.error = false;
                    }
                    this.count--;
                })
                .catch(err => console.error(err));
        }
    }
}
</script>

<style scoped>
</style>
