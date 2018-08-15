/**
 * Current Date/Time Location Widget
 */
import React, { Component } from 'react';
import moment from 'moment';

// intl messages
import IntlMessages from 'Util/IntlMessages';

// rct card box
import { RctCardContent } from 'Components/RctCard';

class CurrentDate extends Component {
    render() {
        return (
            <div className="current-widget bg-success">
                <RctCardContent>
                    <div className="d-flex justify-content-between">
                        <div className="align-items-start">
                            <h3 className="mb-10"><IntlMessages id="widgets.currentDate" /></h3>
                            <h2 className="mb-0">{moment().format('DD MMM YYYY')}</h2>
                        </div>
                        <div className="align-items-end">
                            <i className="zmdi zmdi-calendar"></i>
                        </div>
                    </div>
                </RctCardContent>
            </div>
        );
    }
}

export default CurrentDate;
