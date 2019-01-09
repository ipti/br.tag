/**
 * Current Date/Time Location Widget
 */
import React, { Component } from 'react';

// intl messages
import IntlMessages from 'Util/IntlMessages';

// rct card box
import { RctCardContent } from 'Components/RctCard';

function checkTime(i) {
    if (i < 10) { i = "0" + i };  // add zero in front of numbers < 10
    return i;
}

class CurrentTimeLocation extends Component {

    state = {
        currentTime: {
            hours: '',
            minutes: '',
            seconds: ''
        }
    }

    componentWillMount() {
        let self = this;
        this.timer = setInterval(() => {
            self.startTime();
        }, 500);
    }

    componentWillUnmount() {
        clearInterval(this.timer);
    }

    startTime() {
        let today = new Date();
        let h = today.getHours();
        let m = today.getMinutes();
        let s = today.getSeconds();
        m = checkTime(m);
        s = checkTime(s);
        let time = {
            hours: h,
            minutes: m,
            seconds: s
        }
        this.setState({ currentTime: time });
    }

    render() {
        const { hours, minutes, seconds } = this.state.currentTime;
        return (
            <div className="current-widget bg-info">
                <RctCardContent>
                    <div className="d-flex justify-content-between">
                        <div className="align-items-start">
                            <h3 className="mb-10"><IntlMessages id="widgets.currentTime" /></h3>
                            <h2 className="mb-0">{`${hours} : ${minutes} : ${seconds}`}</h2>
                        </div>
                        <div className="align-items-end">
                            <i className="zmdi zmdi-time"></i>
                        </div>
                    </div>
                </RctCardContent>
            </div>
        );
    }
}

export default CurrentTimeLocation;
