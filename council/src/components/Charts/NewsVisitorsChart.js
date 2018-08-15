/**
 *News Visitors Chart
 * Stacked Bar CHart Component
 */
import React, { Component } from 'react';
import { Bar } from 'react-chartjs-2';

// chart config
import ChartConfig from 'Constants/chart-config';

export default class StackedBarChart extends Component {
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
         scales: {
            xAxes: [{
               gridLines: {
                  color: ChartConfig.chartGridColor
               },
               ticks: {
                  fontColor: ChartConfig.axesColor
               },
               barPercentage:20.0,
               categoryPercentage: 0.05,
               display: false
            }],
            yAxes: [{
               gridLines: {
                  color: ChartConfig.chartGridColor
               },
               ticks: {
                  fontColor: ChartConfig.axesColor
               },
               display: false
            }]
         }
      };
      return (
         <Bar data={data} options={options} height={130} />
      );
   }
}
