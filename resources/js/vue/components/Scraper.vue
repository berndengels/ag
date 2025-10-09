<template>
    <div class="container mt-3" v-show="locations.length > 0">
		<div class="row">
			<div class="col">
				<div class="card">
					<div class="card-header">
						<span v-if="currentLocation">PLZ: {{ currentLocation.zipcode }}</span>
						<span v-else>Suche kann gestartet werden</span>
						<span class="ms-5 float-end text-success" v-if="entity" v-html="entity.name"></span>
					</div>
					<div class="card-body">
						<div class="row">
							<div class="col"><v-chart class="chart" :option="chartOption" :autoresize="true" /></div>
							<div class="col">
								<p v-if="errorMsg" v-html="errorMsg" class="text-danger"></p>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="row mt-3">
			<div class="col-6 float-start">
				<button v-if="!running" class="btn btn-green rounded" @click="scrape">Start Import</button>
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
import { DataZoomInsideComponent, DataZoomSliderComponent } from "echarts/components"
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
    name: "Scraper",
	components: { VChart },
    props: ['modus'],
    data() {
        return {
			postcode: null,
            counter: 0,
			added: 0,
			done: 0,
            locations: [],
            currentLocation: null,
            count: 0,
			remaining: 0,
            success: 0,
            notfound: 0,
            entity: null,
			image: null,
            error: false,
			errorMsg: null,
            info: null,
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
			serieAdded: {
				value: 0,
				name: 'hinzugefügt',
				itemStyle: {
					color: '#00f',
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
							this.serieRemaining,
							this.serieAdded,
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
		added(v) {
			this.serieAdded.value = v
			this.chartOption.series[0].data[4] = this.serieAdded
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
        scrape() {
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
			this.running = false
            clearInterval(this.intVal);
        },
        fetch() {
            this.currentLocation = this.locations[this.counter] ?? null;
            if(this.currentLocation) {
				this.fetchByPostcode(this.currentLocation.zipcode);
            }
        },
		fetchByPostcode(zipcode) {
			axios.get(url + this.modus + "/scrape/" + zipcode)
				.then(resp => {
					if (resp.data.error) {
						this.entity = null;
						this.url = null;
						this.error = true;
						this.image = null;
						this.notfound++;
					} else if (resp.data.entity) {
						if(resp.data.added) {
							this.added++;
						}
						this.errorMsg = null;
						this.url = resp.data.url;
						this.entity = resp.data.entity;
						this.image = resp.data.image;
						this.error = false;
						this.setFound(this.currentLocation);
						this.success++;
					}
				})
				.catch(err => {
					this.errorMsg = err
					this.error = true
					console.info('Error', err)
				});

			this.remaining--;
			this.counter++;
			this.done++;
		},
        setFound(location) {
			if(location) {
				axios.patch(url + this.modus + "/found/" + location.id)
					.then(resp => {
						if(resp.data) {
							this.locations = this.locations.filter(l => l.id !== resp.data.id)
							this.count = this.locations.length;
							this.remaining = this.count;
						}
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
			.img-fluid {
				width: 100%;
				margin: auto;
				border: 1px solid grey;
			}
		}
	}
}
</style>
