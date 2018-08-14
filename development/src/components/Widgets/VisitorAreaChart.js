/**
 * Visitor Area Chart Widget
 */
import React from 'react';
import CountUp from 'react-countup';

// chart
import TinyAreaChart from 'Components/Charts/TinyAreaChart';

// intl messages
import IntlMessages from 'Util/IntlMessages';

// rct card box
import { RctCard, RctCardContent } from 'Components/RctCard';

// chart config
import ChartConfig from 'Constants/chart-config';

// helpers
import { hexToRgbA } from 'Helpers/helpers';

const VisitorAreaChart = ({ data }) => (
    <RctCard>
        <RctCardContent>
            <div className="clearfix">
                <div className="float-left">
                    <h3 className="mb-15 fw-semi-bold"><IntlMessages id="widgets.visitors" /></h3>
                    <div className="d-flex">
                        <div className="mr-50">
                            <span className="fs-14 d-block"><IntlMessages id="widgets.weekly" /></span>
                            <CountUp 
                                separator=","
                                className="counter-point" 
                                start={0} 
                                end={data.weekly} 
                                duration={5} 
                                useEasing={true}
                            />
                        </div>
                        <div className="">
                            <span className="fs-14 d-block"><IntlMessages id="widgets.monthly" /></span>
                            <CountUp separator="," className="counter-point" start={0} end={data.monthly} duration={5} useEasing={true} />
                        </div>
                    </div>
                </div>
                <div className="float-right hidden-md-down">
                    <div className="featured-section-icon">
                        <i className="zmdi zmdi-globe-alt"></i>
                    </div>
                </div>
            </div>
        </RctCardContent>
        <TinyAreaChart
            label="Visitors"
            chartdata={data.chartData.data}
            labels={data.chartData.labels}
            backgroundColor={hexToRgbA(ChartConfig.color.primary, 0.1)}
            borderColor={hexToRgbA(ChartConfig.color.primary, 3)}
            lineTension="0"
            height={70}
            gradient
            hideDots
        />
    </RctCard >
);

export default VisitorAreaChart;
