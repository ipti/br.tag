/**
 * Net Profit Widget
 */
import React from 'react';
import CountUp from 'react-countup';

// chart
import TinyLineChart from 'Components/Charts/TinyLineChart';

// constants
import ChartConfig from 'Constants/chart-config';

// intl messages
import IntlMessages from 'Util/IntlMessages';

// rct card box
import { RctCardContent, RctCard, RctCardFooter } from 'Components/RctCard';

const NetProfit = ({ label, chartdata, labels }) => (
    <RctCard>
        <div className="rct-block-title d-flex justify-content-between">
            <div className="d-flex align-items-start">
                <h4><IntlMessages id="widgets.netProfit" /></h4>
            </div>
            <div className="align-items-end">
                <span className="d-block text-muted counter-point">$<CountUp start={0} end={2478} duration={3} useEasing={true} /></span>
                <p className="text-right mb-0 text-muted">+64%</p>
            </div>
        </div>
        <RctCardContent noPadding>
            <TinyLineChart
                label={label}
                chartdata={chartdata}
                labels={labels}
                borderColor={ChartConfig.color.info}
                pointBackgroundColor={ChartConfig.color.info}
                height={100}
                pointBorderColor="#FFFFFF"
                borderWidth={4}
            />
        </RctCardContent>
        <RctCardFooter customClasses="d-flex justify-content-between">
            {labels && labels.map((label, key) => (
                <span className="fs-12 text-muted" key={key}>{label}</span>
            ))}
        </RctCardFooter>
    </RctCard>
);

export default NetProfit;
