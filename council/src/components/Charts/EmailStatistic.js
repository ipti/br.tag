/**
 * Email Statistics Bar Chart
 */
import React from 'react';
import { Bar } from 'react-chartjs-2';
import ChartConfig from 'Constants/chart-config';

const options = {
  legend: {
    display: false,
    labels: {
      fontColor: ChartConfig.legendFontColor
    }
  },
  scales: {
    xAxes: [{
      gridLines: {
        display: false
      },
      ticks: {
        fontColor: ChartConfig.color.white,
        fontSize: 14,
        beginAtZero: true
      }
    }],
    yAxes: [{
      gridLines: {
        drawBorder: false,
        zeroLineColor: ChartConfig.axesColor,
        color: ChartConfig.axesColor
      },
      ticks: {
        fontColor: ChartConfig.axesColor,
        stepSize: 10,
        display: false,
        beginAtZero: true
      }
    }]
  }
};

const EmailStatistic = ({ datasets, labels }) => {
  const data = {
    labels,
    datasets
  }
  return (
    <Bar height={240} data={data} options={options} />
  );
}

export default EmailStatistic;
