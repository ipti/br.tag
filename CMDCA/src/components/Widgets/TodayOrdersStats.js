/**
 * Today Orders Stats
 */
import React from 'react';
import CountUp from 'react-countup';

// intl messages
import IntlMessages from 'Util/IntlMessages';

// rct card box
import { RctCardContent } from 'Components/RctCard';

const TotalOrderStats = () => (
    <div className="current-widget bg-primary">
        <RctCardContent>
            <div className="d-flex justify-content-between">
                <div className="align-items-start">
                    <h3 className="mb-10"><IntlMessages id="widgets.todayOrders" /></h3>
                    <h2 className="mb-0"><CountUp start={0} end={14255} /></h2>
                </div>
                <div className="align-items-end">
                    <i className="zmdi zmdi-time"></i>
                </div>
            </div>
        </RctCardContent>
    </div>
);

export default TotalOrderStats;
