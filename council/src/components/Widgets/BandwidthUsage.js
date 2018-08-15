/**
 * Bandwidth Usage Widget
 */
import React, { Component } from 'react';
import ReactSpeedometer from "react-d3-speedometer";

class BandwidthUsageWidget extends Component {

    state = {
        bandwidthUsage: 200
    }

    componentDidMount() {
        let self = this;
        this.timerHandle = setInterval(() => {
            self.setState({ bandwidthUsage: Math.floor(Math.random() * 1000) + 1 })
        }, 1500);
    }

    componentWillUnmount() {
        if (this.timerHandle) {
            clearTimeout(this.timerHandle);
            this.timerHandle = 0;
        }
    }

    render() {
        return (
            <div className="card">
                <ReactSpeedometer
                    value={this.state.bandwidthUsage}
                    startColor="red"
                    endColor="green"
                    needleColor="steelblue"
                    height={200}
                    ringWidth={40}
                    needleColor="#895DFF"
                    currentValueText="Bandwidth Usage: ${value} Kb"
                />
            </div>
        );
    }
}

export default BandwidthUsageWidget;
