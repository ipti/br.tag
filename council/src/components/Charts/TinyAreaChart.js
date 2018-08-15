/**
 * Tiny Area Chart
 */
import React, { Component } from 'react';
import { Line } from 'react-chartjs-2';

// chart options
const options = {
    legend: {
        display: false
    },
    scales: {
        xAxes: [{
            display: false
        }],
        yAxes: [{
            display: false
        }]
    }
};

// Main Component
export default class TinyAreaChart extends Component {
    render() {
        const { labels, label, backgroundColor, borderColor, chartdata, lineTension, height, gradient, hideDots } = this.props;
        const data = (canvas) => {
            const ctx = canvas.getContext("2d");
            var gradientFill = ctx.createLinearGradient(0, 170, 0, 50);
            gradientFill.addColorStop(0, "rgba(255, 255, 255, 0)");
            gradientFill.addColorStop(1, backgroundColor);

            return {
                labels: labels,
                datasets: [
                    {
                        label: label,
                        fill: true,
                        lineTension: lineTension,
                        fillOpacity: 0.3,
                        backgroundColor: gradient ? gradientFill : backgroundColor,
                        borderColor: borderColor,
                        borderWidth: 3,
                        pointBackgroundColor: borderColor,
                        pointBorderWidth: 2,
                        pointRadius: hideDots ? 0 : 4,
                        pointBorderColor: '#FFF',
                        pointHoverRadius: 1,
                        pointHoverBorderWidth: 2,
                        data: chartdata
                    }
                ]
            }
        }
        return (
            <Line data={data} options={options} height={height} />
        );
    }
}
