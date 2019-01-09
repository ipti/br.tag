import React, { Component } from 'react';
import { Doughnut } from 'react-chartjs-2';
import ChartConfig from 'Constants/chart-config';

const data = {
   labels: [
      'Total Request',
      'New',
      'Pending'
   ],
   datasets: [{
      data: [250, 25, 125],
      backgroundColor: [
         ChartConfig.color.primary,
         ChartConfig.color.warning,
         ChartConfig.color.info
      ],
      hoverBackgroundColor: [
         ChartConfig.color.primary,
         ChartConfig.color.warning,
         ChartConfig.color.info
      ]
   }]
};

const options = {
   legend: {
      display: false,
      labels: {
         fontColor: ChartConfig.legendFontColor
      }
   },
   cutoutPercentage: 50
};

export default class DoughnutChart extends Component {

   render() {
      return (
         <Doughnut data={data} options={options} height={100} />
      );
   }
}