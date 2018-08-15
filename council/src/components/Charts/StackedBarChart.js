/**
 * Overall Traffic Stats
 * Stacked Bar CHart Component
 */
import React, { Component } from 'react';
import { Bar } from 'react-chartjs-2';

// chart config
import ChartConfig from 'Constants/chart-config';

export default class BarChart extends Component {
	render() {
		const { labels, datasets } = this.props;
		const data = {
			labels,
			datasets
		}
		const options = {
			legend: {
			display: false
			},
			layout: {
			padding: {
				left: 20,
				right: 20,
				top: 20,
				bottom: 20
			}
			},
			scales: {
			xAxes: [{
				gridLines: {
					color: ChartConfig.chartGridColor
				},
				ticks: {
					fontColor: ChartConfig.axesColor
				},
				barPercentage: 1.0,
				categoryPercentage: 0.4,
				display: true
			}],
			yAxes: [{
				gridLines: {
					color: ChartConfig.chartGridColor
				},
				ticks: {
					fontColor: ChartConfig.axesColor
				}
			}]
			}
		};
		return (
			<Bar data={data} options={options} height={150} />
		);
	}
}
