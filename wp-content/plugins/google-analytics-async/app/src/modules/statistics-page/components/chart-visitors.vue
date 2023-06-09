<template>
	<div class="beehive-tabs">
		<div class="beehive-tabs-tablist">
			<button
				v-for="( item, name ) in getSummary"
				class="beehive-tab"
				aria-controls="beehive-visitors-chart"
				:key="name"
				:class="buttonClass(name)"
				:aria-selected="ariaSelected(name)"
				:tabindex="tabIndex(name)"
				@click="changeTab(name)"
			>
				<span class="beehive-tab-title" aria-hidden="true">{{ getTitle( name ) }}</span>

				<span class="beehive-tab-value-wrap" aria-hidden="true">
					<span class="beehive-tab-value" v-if="'bounce_rates' === name">{{ item.value }}%</span>
					<span class="beehive-tab-value" v-else>{{ item.value }}</span>

					<span class="beehive-tab-trend beehive-green" v-if="item.trend < 0 && 'bounce_rates' === name">
						<i class="sui-icon-arrow-down sui-sm" aria-hidden="true"></i>
						{{ Math.abs( item.trend ) }}%
					</span>
					<span class="beehive-tab-trend beehive-red" v-else-if="item.trend < 0">
						<i class="sui-icon-arrow-down sui-sm" aria-hidden="true"></i>
						{{ Math.abs( item.trend ) }}%
					</span>
					<span
						class="beehive-tab-trend beehive-red"
						v-else-if="item.trend > 0 && 'bounce_rates' === name"
					>
						<i class="sui-icon-arrow-up sui-sm" aria-hidden="true"></i>
						{{ Math.abs( item.trend ) }}%
					</span>
					<span class="beehive-tab-trend beehive-green" v-else-if="item.trend > 0">
						<i class="sui-icon-arrow-up sui-sm" aria-hidden="true"></i>
						{{ Math.abs( item.trend ) }}%
					</span>
				</span>

				<span class="sui-screen-reader-text">{{ getTitle( name ) }}</span>
			</button>
		</div>
		<div id="beehive-visitors-chart" class="beehive-tab-panel">
			<line-chart
				:id="chartId"
				class="beehive-chart"
				role="img"
				aria-hidden="true"
				:chart-data="chartData"
				:options="getOptions"
			/>
			<div tabindex="-1" class="beehive-options-sidenote" aria-hidden="true" v-if="compare">
				<span class="beehive-sidenote-left">
					<i class="beehive-sidenote-indicator" :style="getLegendStyle(0)" aria-hidden="true"></i>
					{{ $i18n.statistics.labels.current_period }}
				</span>
				<span class="beehive-sidenote-right">
					<i class="beehive-sidenote-indicator" :style="getLegendStyle(1)" aria-hidden="true"></i>
					{{ $i18n.statistics.labels.previous_period }}
				</span>
			</div>
			<p
				class="sui-screen-reader-text"
				v-if="isEmpty"
			>{{ $i18n.statistics.descriptions.empty_visitors_chart }}</p>
		</div>
	</div>
</template>

<script>
import moment from 'moment'
import LineChart from '@/components/charts/line-chart'

export default {
	name: 'ChartVisitors',

	props: ['stats', 'compare', 'periods'],

	components: {
		LineChart
	},

	data() {
		return {
			chartId: 'beehive-visitors-line-chart',
			selectedTab: 'sessions', // Default item.
			sections: {
				sessions: {
					color: ['#17A8E3', '#ADDCF2'],
					title: this.$i18n.statistics.labels.sessions
				},
				users: {
					color: ['#2D8CE2', '#9DD0FF'],
					title: this.$i18n.statistics.labels.users
				},
				pageviews: {
					color: ['#8D00B1', '#E9CCF0'],
					title: this.$i18n.statistics.labels.pageviews
				},
				page_sessions: {
					color: ['#3DB8C2', '#C0EBEF'],
					title: this.$i18n.statistics.labels.page_sessions
				},
				average_sessions: {
					color: ['#2B7BA1', '#C0EBEF'],
					title: this.$i18n.statistics.labels.average_sessions
				},
				bounce_rates: {
					color: ['#FFB17C', '#FFE3CF'],
					title: this.$i18n.statistics.labels.bounce_rates
				}
			},
			defaultChartOptions: {
				legend: {
					display: false
				},
				scales: {
					yAxes: [
						{
							gridLines: {
								display: true,
								color: '#E6E6E6',
								zeroLineColor: '#E6E6E6',
								drawBorder: false // Allow zeroLineColor on xAxes.
							},
							ticks: {
								fontColor: '#676767',
								fontSize: 11
							}
						}
					],
					xAxes: [
						{
							gridLines: {
								display: true,
								zeroLineColor: 'rgba(0,0,0,0)',
								drawBorder: false // Allow zeroLineColor on xAxes.
							},
							ticks: {
								fontColor: '#676767',
								fontSize: 11
							}
						}
					]
				},
				tooltips: {
					xPadding: 15,
					yPadding: 15,
					backgroundColor: 'rgba(51,51,51,0.85)',
					titleFontColor: '#FFFFFF',
					titleFontSize: 14,
					titleFontFamily: 'Roboto',
					titleFontStyle: 'bold',
					titleAlign: 'left',
					titleSpacing: 0,
					titleMarginBottom: 10,
					bodyFontColor: '#FFFFFF',
					bodyFontSize: 14,
					bodyFontFamily: 'Roboto',
					bodyFontStyle: 'normal',
					bodySpacing: 10,
					bodyAlign: 'left',
					cornerRadius: 4,
					displayColors: false,
					mode: 'index',
					intersect: false
				},
				responsive: true,
				maintainAspectRatio: false
			},
			chartColors: {},
			chartOptions: {},
			chartData: {
				labels: [],
				datasets: []
			},
			tooltipTitles: {}
		}
	},

	watch: {
		// When stats change, update chart.
		stats(newStats) {
			this.changeStatsChart()
		},

		// When tab is changed, update the chart.
		selectedTab(tab) {
			this.changeStatsChart()
		},

		// When comparison checkbox is checked.
		compare(compare) {
			this.changeComparison()
		}
	},

	computed: {
		/**
		 * Check if the chart data is empty.
		 *
		 * @since 3.2.4
		 *
		 * @returns {boolean}
		 */
		isEmpty() {
			return Object.keys(this.stats).length <= 0
		},

		/**
		 * Get summary data for the chart.
		 *
		 * @since 3.2.5
		 *
		 * @returns {object}
		 */
		getSummary() {
			if (this.stats.summary) {
				let items = {}

				Object.keys(this.stats.summary).forEach(name => {
					if (this.canShow(name)) {
						items[name] = this.stats.summary[name]
					}
				})

				return items
			} else {
				return {}
			}
		},

		/**
		 * Get the current period legend text.
		 *
		 * @since 3.2.7
		 *
		 * @returns {string}
		 */
		getCurrentPeriodText() {
			return this.getPeriodLegend(
				this.periods.current.from,
				this.periods.current.to
			)
		},

		/**
		 * Get the previous period legend text.
		 *
		 * @since 3.2.7
		 *
		 * @returns {string}
		 */
		getPreviousPeriodText() {
			return this.getPeriodLegend(
				this.periods.previous.from,
				this.periods.previous.to
			)
		},

		/**
		 * Get tab button active class.
		 *
		 * @since 3.2.4
		 *
		 * @returns {object}
		 */
		buttonClass() {
			return function(tab) {
				return {
					'beehive-active': this.selectedTab === tab
				}
			}
		},

		/**
		 * Get the aria-selected attribute value.
		 *
		 * @since 3.2.4
		 *
		 * @returns {object}
		 */
		ariaSelected() {
			return function(tab) {
				// Should be string.
				return tab === this.selectedTab ? 'true' : 'false'
			}
		},

		/**
		 * Get the tab-index attribute value.
		 *
		 * @since 3.2.4
		 *
		 * @returns {object}
		 */
		tabIndex() {
			return function(tab) {
				// Should be string.
				return tab === this.selectedTab ? -1 : false
			}
		},

		/**
		 * Get the legend style data.
		 *
		 * @since 3.2.4
		 *
		 * @returns {object}
		 */
		getLegendStyle() {
			return function(index) {
				return {
					'background-color': this.sections[this.selectedTab].color[
						index
					]
				}
			}
		},

		/**
		 * Get the chart options object.
		 *
		 * @since 3.2.4
		 *
		 * @returns {object}
		 */
		getOptions() {
			if (this.isEmpty) {
				return {
					responsive: true,
					maintainAspectRatio: false
				}
			} else {
				return this.chartOptions
			}
		}
	},

	methods: {
		/**
		 * Check if the current item can be shown.
		 *
		 * @since 3.2.4
		 *
		 * @returns {boolean}
		 */
		canShow(name) {
			return this.sections.hasOwnProperty(name)
		},

		/**
		 * Get the legend period text.
		 *
		 * @since 3.2.7
		 *
		 * @returns {string}
		 */
		getPeriodLegend(from, to) {
			if (from === to) {
				let start = moment(from)
				return start.format('MMM D, YYYY')
			} else {
				let start = moment(from)
				let end = moment(to)

				return (
					start.format('MMM D, YYYY') +
					' - ' +
					end.format('MMM D, YYYY')
				)
			}
		},

		/**
		 * Get the title of the item.
		 *
		 * @since 3.2.4
		 *
		 * @returns {string}
		 */
		getTitle(name) {
			return this.sections[name].title
		},

		/**
		 * Change the currently selected tab.
		 *
		 * @since 3.2.4
		 */
		changeTab(tab) {
			this.selectedTab = tab
		},

		/**
		 * Update the chart when stats are changed.
		 *
		 * @since 3.2.4
		 */
		changeStatsChart() {
			// When data is empty.
			if (this.isEmpty) {
				this.chartData = {
					labels: [],
					datasets: []
				}
				this.chartOptions = this.defaultChartOptions

				return
			}

			let vm = this

			let chartLabels = []
			let currentData = []
			let chartLinesX = []

			let chartOptions = this.defaultChartOptions

			// Get the colors.
			let colors = this.sections[this.selectedTab].color

			// Get the title.
			let title = this.getTitle(vm.selectedTab)

			let chartData = {
				labels: [],
				datasets: [
					{
						label: title,
						data: [],
						borderWidth: 2,
						borderColor: colors[0],
						backgroundColor: 'rgba(0,0,0,0)',
						pointRadius: 4,
						pointBorderColor: colors[0],
						pointBackgroundColor: '#FFFFFF',
						pointHoverBackgroundColor: colors[0]
					}
				]
			}

			// Get stats item.
			const stats = vm.stats[vm.selectedTab].current

			// Setup data set for the current period.
			Object.keys(stats).forEach(function(key, idx, array) {
				chartLabels.push(stats[key][0])
				currentData.push(stats[key][1])

				if (idx === array.length - 1) {
					chartLinesX.push('rgba(0,0,0,0)')
				} else {
					chartLinesX.push('#E6E6E6')
				}
			})

			// Set each grid lines color.
			chartOptions.scales.xAxes[0].gridLines['color'] = chartLinesX

			// Tooltip callbacks.
			chartOptions.tooltips.callbacks = {}

			chartOptions.tooltips.callbacks.label = tooltipItem => {
				let value = tooltipItem.value
				let index = tooltipItem.index
				if (vm.selectedTab === 'average_sessions') {
					value = moment.utc(value * 1000).format('HH:mm:ss')
				} else if (vm.selectedTab === 'bounce_rates') {
					value = value + '%'
				}

				if (!this.compare) {
					return title + ' : ' + value
				} else if (tooltipItem.datasetIndex === 1) {
					return (
						this.$i18n.statistics.labels.previous_period +
						' : ' +
						value
					)
				} else {
					return (
						this.$i18n.statistics.labels.current_period +
						' : ' +
						value
					)
				}
			}

			// Set title callback.
			chartOptions.tooltips.callbacks.title = tooltipItem => {
				if (this.compare) {
					return title
				} else {
					return tooltipItem[0].label
				}
			}

			// Set label.
			chartData.datasets[0].label = title

			// Update the data.
			chartData.labels = chartLabels
			chartData.datasets[0].data = currentData

			if (0 === currentData.length) {
				// Replace chart colors when data is empty.
				chartData.datasets[0].borderColor = 'rgba(0, 0, 0, 0)'
				chartData.datasets[0].pointRadius = 0
				chartData.datasets[0].pointBorderColor = 'rgba(0, 0, 0, 0)'
				chartData.datasets[0].pointBackgroundColor = 'rgba(0, 0, 0, 0)'
				chartData.datasets[0].pointHoverBackgroundColor =
					'rgba(0, 0, 0, 0)'

				// Hide tooltip when data is empty.
				chartOptions.tooltips['enabled'] = false

				// Replace some chart options to show a clean chart.
				chartOptions.scales.yAxes[0].ticks['suggestedMin'] = 40000
				chartOptions.scales.yAxes[0].ticks['suggestedMax'] = 44000
				chartOptions.scales.yAxes[0].ticks['beginAtZero'] = true
				chartOptions.scales.yAxes[0].ticks['maxTicksLimit'] = 5
			}

			// If comparison checkbox is checked.
			if (this.compare) {
				chartData.datasets.push(this.getPrevPeriodDataSet())
			}

			this.chartData = chartData

			this.chartOptions = chartOptions
		},

		/**
		 * Change the chart comparison option.
		 *
		 * Make changes to chart when comparison checkbox is checked.
		 *
		 * @since 3.2.4
		 */
		changeComparison() {
			if (this.isEmpty) {
				return
			}

			if (this.compare) {
				// Add previous data to dataset.
				this.chartData.datasets.push(this.getPrevPeriodDataSet())
			} else if (this.chartData.datasets.length > 1) {
				// Remove previous data.
				this.chartData.datasets.splice(1, 1)
			}

			// Now update the chart.
			this.$root.$emit('updateLineChart', {
				chart: this.chartId
			})
		},

		/**
		 * Get the previous period data with options.
		 *
		 * @since 3.2.4
		 *
		 * @returns {object}
		 */
		getPrevPeriodDataSet() {
			let data = []

			// Previous period data.
			let stats = this.stats[this.selectedTab].previous

			// Get the title.
			let title = this.getTitle(this.selectedTab)

			// Color codes.
			let colors = this.sections[this.selectedTab].color

			// Setup data set for the previous period.
			Object.keys(stats).forEach(function(key) {
				data.push(stats[key][1])
			})

			return {
				label: title,
				data: data,
				borderWidth: 2,
				borderColor: colors[1],
				backgroundColor: 'rgba(0,0,0,0)',
				pointRadius: 4,
				pointBorderColor: colors[1],
				pointBackgroundColor: '#FFFFFF',
				pointHoverBackgroundColor: colors[1]
			}
		}
	}
}
</script>
