/**
 * Sales Chart Component
 */
import React, { Component } from 'react';
import { Line } from 'react-chartjs-2';

// chart config file
import ChartConfig from 'Constants/chart-config';

// Main Component
export default class SalesChart extends Component {
    render() {
        const { labels, label, borderColor, chartdata, pointBackgroundColor, height, pointBorderColor, borderWidth } = this.props;
        const data = (canvas) => {
            const ctx = canvas.getContext("2d");
            const _stroke = ctx.stroke;
            ctx.stroke = function () {
                ctx.save();
                ctx.shadowColor = ChartConfig.shadowColor;
                ctx.shadowBlur = 13;
                ctx.shadowOffsetX = 0;
                ctx.shadowOffsetY = 12;
                _stroke.apply(this, arguments);
                ctx.restore();
            };
            return {
                labels: labels,
                datasets: [
                    {
                        label: label,
                        fill: false,
                        lineTension: 0,
                        fillOpacity: 0.3,
                        borderColor: borderColor,
                        borderWidth: borderWidth,
                        pointBorderColor: pointBorderColor,
                        pointBackgroundColor: pointBackgroundColor,
                        pointBorderWidth: 3,
                        pointRadius: 6,
                        pointHoverBackgroundColor: pointBackgroundColor,
                        pointHoverBorderColor: pointBorderColor,
                        pointHoverBorderWidth: 4,
                        pointHoverRadius: 7,
                        data: chartdata,
                    }
                ]
            }
        }
        // chart options
        const options = {
            legend: {
                display: false
            },
            scales: {
                xAxes: [{
                    display: true,
                    ticks: {
                        min: 0
                    },
                    gridLines: {
                        display: true,
                        drawBorder: false
                    }
                }],
                yAxes: [{
                    display: false,
                    ticks: {
                        suggestedMin: 0,
                        beginAtZero: true
                    }
                }]
            }
        };
        return (
            <Line data={data} options={options} height={height} />
        );
    }
}
