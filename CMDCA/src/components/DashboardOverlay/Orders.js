/**
 * Orders Stats
 */
import React from 'react';
import CountUp from 'react-countup';

// chart config
import ChartConfig from 'Constants/chart-config';

// chart
import TinyAreaChart from 'Components/Charts/TinyAreaChart';

// collapsible card
import RctCollapsibleCard from 'Components/RctCollapsibleCard/RctCollapsibleCard';

// intl messages
import IntlMessages from 'Util/IntlMessages';

const OrdersStats = () => (
    <RctCollapsibleCard
        heading={<IntlMessages id="widgets.orders" />}
        fullBlock
    >
        <div className="d-flex justify-content-between p-20">
            <div className="counter-report">
                <h2 className="title mb-0"><CountUp start={0} end={25875} /></h2>
                <span className="text-muted">Total Orders</span>
            </div>
            <span className="align-self-center d-flex arrow-icon"><i className="ti-arrow-up"></i></span>
        </div>
        <div className="mb-10">
            <TinyAreaChart
                label="Orders"
                chartdata={[542, 480, 430, 550, 530, 453, 380, 434, 568, 610, 700, 630]}
                labels={["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"]}
                backgroundColor={ChartConfig.color.info}
                borderColor={ChartConfig.color.info}
                lineTension={0}
                height={110}
                gradient
                hideDots
            />
        </div>
        <div className="d-flex justify-content-between p-20">
            <div className="totle-status">
                <h2><CountUp start={0} end={720} /></h2>
                <span>Today</span>
            </div>
            <div className="totle-status">
                <h2><CountUp start={0} end={1500} /></h2>
                <span>This Week</span>
            </div>
            <div className="totle-status">
                <h2><CountUp start={0} end={2522} /></h2>
                <span>This Month</span>
            </div>
        </div>
    </RctCollapsibleCard>
);

export default OrdersStats;
