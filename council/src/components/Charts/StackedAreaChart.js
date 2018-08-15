/**
 * Stacked Area Chart
 */
import React, { Component } from 'react';
import { Line } from 'react-chartjs-2';
import ChartConfig from 'Constants/chart-config';

const options = {
  legend: {
    display: false,
    labels: {
      fontColor: ChartConfig.legendFontColor,
      usePointStyle: true
    }
  },
  scales: {
    xAxes: [{
      gridLines: {
        color: ChartConfig.chartGridColor,
        display: false
      },
      ticks: {
        fontColor: ChartConfig.axesColor
      }
    }],
    yAxes: [{
      gridLines: {
        color: ChartConfig.chartGridColor
      },
      ticks: {
        fontColor: ChartConfig.axesColor,
        min: 100,
        max: 800
      }
    }]
  }
};

// Main Component
export default class StackedAreaChartComponent extends Component {
  render() {
    const { labels, datasets } = this.props;
    const data = {
      labels,
      datasets
    };
    return (
      <Line data={data} options={options} height={60} />
    );
  }
}
