<template>
    <div class="container mt-3" v-show="locations.length > 0">
		<div class="row">
			<div class="col">
				<div class="card">
					<div class="card-header">
						<span v-if="currentLocation">{{ currentLocation.plz }} {{ currentLocation.name }}</span>
						<span v-else>Suche kann gestartet werden</span>
						<span class="ms-5 float-end text-success" v-if="entity" v-html="entity.name"></span>
					</div>
					<div class="card-body">
						<v-chart class="chart" :option="chartOption" :autoresize="true" />
					</div>
				</div>
			</div>
		</div>
		<div class="row mt-3">
			<div class="col-6 float-start">
				<button v-if="!running" class="btn btn-green rounded" @click="crawle">Start Import</button>
				<button v-if="running" class="btn btn-red rounded" @click="stop">Stop Import</button>
			</div>
			<div class="col-6 float-end">
				<div class="float-right">
					<input id="postcode" v-model="postcode" class="form-control w-50 d-inline-block" placeholder="Postleitzahl" />
					<button v-if="!running" class="btn btn-green rounded d-inline-block ms-3" @click="search">Suche PLZ</button>
				</div>
			</div>
		</div>
    </div>
</template>

<script>
import axios from "axios";

import { use } from "echarts/core"
import { CanvasRenderer } from "echarts/renderers"
import { PieChart } from "echarts/charts"
import {DataZoomInsideComponent, DataZoomSliderComponent} from "echarts/components"
import {
	TooltipComponent,
	LegendComponent,
	ToolboxComponent,
	GridComponent
} from "echarts/components"
import VChart, {THEME_KEY} from "vue-echarts"
//import { shallowRef } from "vue"

use([
	CanvasRenderer,
	PieChart,
	TooltipComponent,
	LegendComponent,
	ToolboxComponent,
	GridComponent,
	DataZoomSliderComponent,
	DataZoomInsideComponent,
])

const url = 'https://ag.test/api/',
    minDelay = 1000,
    maxDelay = 2000;

export default {
    name: "Crawler",
	components: {VChart},
    props: ['modus'],
    data() {
        return {
			postcode: null,
            counter: 0,
			done: 0,
            locations: [],
            currentLocation: null,
            count: 0,
			remaining: 0,
            success: 0,
            notfound: 0,
            entity: null,
            error: false,
            info: null,
            image: null,
            url: null,
            intVal: null,
            running: false,
			serieFound: {
				value: 0,
				name: 'gefunden',
				itemStyle: {
					color: '#080',
				}
			},
			serieNotFound: {
				value: 0,
				name: 'nicht gefunden',
				itemStyle: {
					color: '#e00',
				}
			},
			serieRemaining: {
				value: 0,
				name: 'verbleibend',
				itemStyle: {
					color: '#f90',
				}
			},
			chartOption: {
				tooltip: {
					trigger: 'item',
					formatter: '{b}: {c}'
				},
				legend: {
					orient: 'vertical',
					left: 'left',
				},
				series: [
					{
						type: 'pie',
						radius: '50%',
						label: {
							position: 'outside',
							show: true,
							fontSize: 14,
							formatter: '{b}: {c}',
							fontWeight: 'bold',
							shadowColor: '#00f',
							shadowBlur: 3,
						},
						selectedMode: 'single',
						data: [
							this.serieFound,
							this.serieNotFound,
							this.serieRemaining
						],
						emphasis: {
							itemStyle: {
								shadowBlur: 10,
								shadowOffsetX: 0,
								shadowColor: 'rgba(0, 0, 0, 0.5)'
							}
						}
					}
				]
			}
        }
    },
    mounted() {
		this.getDone();
        this.getLocations();
    },
	computed: {
		progress() {
			return (this.done * 100 / this.count).toFixed(1)
		},
		progressPercent() {
			return (this.success * 500 / this.count).toFixed(1)
		},
	},
	watch: {
		success(v) {
			this.serieFound.value = v
			this.chartOption.series[0].data[1] = this.serieFound
		},
		notfound(v) {
			this.serieNotFound.value = v
			this.chartOption.series[0].data[2] = this.serieNotFound
		},
		remaining(v) {
			this.serieRemaining.value = v
			this.chartOption.series[0].data[3] = this.serieRemaining
		},
	},
    methods: {
		makeProgress() {
			if(this.success < this.count) {
				this.progress = 5;
			}
		},
		getRandomInt(min, max) {
            min = Math.ceil(min);
            max = Math.floor(max);
            return Math.floor(Math.random() * (max - min) + min); // The maximum is exclusive and the minimum is inclusive
        },
		getDone() {
			axios.get(url + this.modus + "/count")
				.then(resp => {
					this.done = parseInt(resp.data.count);
				})
				.catch(err => console.error(err));
		},
        getLocations() {
            axios.get(url + this.modus + "/locations")
                .then(resp => {
                    this.locations = resp.data.locations;
                    this.count = resp.data.locations.length;
					this.remaining = this.count;
                    this.currentLocation = this.locations[this.counter];
                })
                .catch(err => console.error(err));
        },
		search() {
			this.fetchByPostcode(this.postcode);
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
//				axios.get(url + this.modus + "/crawle/" + this.currentLocation.plz + "/" + this.currentLocation.name)
				this.fetchByPostcode(this.currentLocation.plz);
            }
        },
		fetchByPostcode(plz) {
			axios.get(url + this.modus + "/crawle/" + plz)
				.then(resp => {
					if (resp.data.error) {
						this.entity = null;
						this.image = null;
						this.url = null;
						this.error = true;
						this.notfound++;
					} else if (resp.data.entity) {
						this.url = resp.data.url;
						this.entity = resp.data.entity;
//                            this.image = resp.data.image;
						this.error = false;
						this.setFound(this.currentLocation);
						this.success++;
					}
					this.remaining--;
					this.counter++;
					this.done++;
				})
				.catch(err => console.error(err));
		},
        setFound(location) {
			if(location) {
				axios.patch(url + this.modus + "/found/" + location.id)
					.then(resp => {
					})
					.catch(err => console.error(err));
			}
        }
    }
}
</script>

<style scoped>
.container {
	.card {
		font-size: 1.0rem;
		color: #666;
		font-weight: 200;
		.card-header {
			font-weight: bold;
		}
		.card-body {
			min-height: 600px;
			.chart {
				width: 500px;
				height: 500px;
				z-index: 1000;
			}
			.shell {
				height: 20px;
				width: 500px;
				border: 1px solid #aaa;
				border-radius: 13px;
				padding: 0;
				.bar {
					background: linear-gradient(to right, #B993D6, #8CA6DB);
					height: 20px;
					width: 15px;
					border-radius: 9px;
					vertical-align: middle;
					span {
						display: inline-block;
						height: 100%;
						padding: 0;
						vertical-align: middle;
						white-space: nowrap;
						text-align: center;
						line-height: 20px;
						color: #008800;
						font-size: 0.7em
					}
				}
			}
		}
	}
}
</style>
