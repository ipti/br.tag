/**
 * Dynamic Line Chart Component
 */
import React, { Component, Fragment } from 'react';
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

const initialState = {
    labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July'],
    datasets: [
        {
            label: 'My First dataset',
            backgroundColor: 'rgba(255,255,255,0.4)',
            borderColor: 'rgba(255,255,255,1)',
            borderWidth: 2,
            hoverBackgroundColor: 'rgba(144,97,255,0.4)',
            hoverBorderColor: 'rgba(144,97,255,0.4)',
            data: [65, 59, 80, 81, 56, 55, 40]
        }
    ]
};

export default class DynamicLineChart extends Component {

    componentDidMount() {
        this.setState(initialState);

        var _this = this;

        this.timerFunction = setInterval(function () {
            var oldDataSet = _this.state.datasets[0];
            var newData = [];

            for (var x = 0; x < _this.state.labels.length; x++) {
                newData.push(Math.floor(Math.random() * 100));
            }

            var newDataSet = {
                ...oldDataSet
            };

            newDataSet.data = newData;

            var newState = {
                ...initialState,
                datasets: [newDataSet]
            };

            _this.setState(newState);
        }, 1500);
    }

    componentWillUnmount() {
        clearInterval(this.timerFunction);
    }

    render() {
        return (
            <Fragment>
                {this.state &&
                    <Line data={this.state} options={options} height={90} />
                }
            </Fragment>
        );
    }
}
