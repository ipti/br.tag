/**
 * Product Stats Chart
 */
import React, { Component } from 'react';
import { Line } from 'react-chartjs-2';

// chart config
import ChartConfig from 'Constants/chart-config';

// options
const options = {
    elements: {
        point: {
            radius: 0
        }
    },
    legend: {
        display: false,
        labels: {
            fontColor: ChartConfig.legendFontColor
        }
    },
    scales: {
        xAxes: [{
            gridLines: {
                offsetGridLines: true,
                display: false
            },
            ticks: {
                fontColor: ChartConfig.axesColor
            }
        }],
        yAxes: [{
            gridLines: {
                drawBorder: false,
                zeroLineColor: ChartConfig.chartGridColor
            },
            ticks: {
                fontColor: ChartConfig.axesColor,
                stepSize: 1000,
                beginAtZero: true,
                padding: 40
            }
        }]
    }
};

class ProductStatsChart extends Component {
    render() {
        const { labels, datasets } = this.props;
        const data = (canvas) => {
            const ctx = canvas.getContext("2d");
            const _stroke = ctx.stroke;
            ctx.stroke = function () {
                ctx.save();
                ctx.shadowColor = 'rgba(0,0,0,0.5)';
                ctx.shadowBlur = 13;
                ctx.shadowOffsetX = 0;
                ctx.shadowOffsetY = 12;
                _stroke.apply(this, arguments);
                ctx.restore();
            };
            return {
                labels,
                datasets
            }
        }
        return (
            <Line data={data} options={options} height={95} />
        );
    }
}

export default ProductStatsChart;
