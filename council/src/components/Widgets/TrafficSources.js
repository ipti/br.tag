/**
 * Traffic Sources Widget
 */
import React, { Component } from 'react';
import { Line } from 'react-chartjs-2';

// intl messages
import IntlMessages from 'Util/IntlMessages';

// chart config file
import ChartConfig from 'Constants/chart-config';

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
                drawBorder: false
            },
            ticks: {
                fontColor: ChartConfig.axesColor,
                display: false
            }
        }],
        yAxes: [{
            gridLines: {
                drawBorder: false,
                zeroLineColor: ChartConfig.chartGridColor,
                display: false
            },
            ticks: {
                fontColor: ChartConfig.axesColor,
                display: false,
                stepSize: 60,
                beginAtZero: true,
                padding: 40
            }
        }]
    }
};

class TrafficSourcesWidget extends Component {
    render() {
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
                labels: this.props.data.chartData.labels,
                datasets: [
                    {
                        label: 'Traffic',
                        fill: false,
                        tension: 0,
                        backgroundColor: ChartConfig.color.info,
                        borderColor: ChartConfig.color.info,
                        borderWidth: 3,
                        pointBorderWidth: 0,
                        hoverBackgroundColor: ChartConfig.color.info,
                        hoverBorderColor: ChartConfig.color.info,
                        data: this.props.data.chartData.data
                    }
                ]
            }
        }
        return (
            <div className="card resource-mh">
                <h4 className="card-title"><IntlMessages id="widgets.trafficSources" /></h4>
                <span className="fs-14 mb-4">{this.props.data.totalActiveUsers} <IntlMessages id="widgets.totalActiveUsers" /></span>
                <Line data={data} options={options} height={110} />
            </div>
        );
    }
}

export default TrafficSourcesWidget;
