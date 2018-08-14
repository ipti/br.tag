//Subscribers Widget

import React, { Component } from 'react'
import CountUp from 'react-countup';

// chart
import SubscriberDoughnutChart from 'Components/Charts/SubscriberDoughnutChart';

export default class Subscribers extends Component {
   render() {
      return (
         <div className="overlay-wrap">
            <SubscriberDoughnutChart />
            <div className="overlay-content d-flex justify-content-center align-items-center">
               <CountUp
                  separator=","
                  className="count-value text-muted fw-bold"
                  start={0}
                  end={15800}
                  duration={5}
                  useEasing={true}
               />
            </div>
         </div>
      )
   }
}
