/**
 * New Order Countdown Widget
 */
import React, { Component } from 'react';
import { Card, CardBody } from 'reactstrap';
import IconButton from '@material-ui/core/IconButton';

// components
import CountDown from 'Components/CountDown/CountDown';

// intl messages
import IntlMessages from 'Util/IntlMessages';

export default class NewOrderCountdown extends Component {
    render() {
        return (
            <Card className="rct-block">
                <CardBody className="d-flex">
                    <div>
                        <span className="d-flex justify-content-center align-items-center rounded-circle bg-warning p-15 mr-15">
                            <i className="zmdi zmdi-notifications-active zmdi-hc-lg text-white"></i>
                        </span>
                    </div>
                    <div>
                        <p className="fs-14 fw-bold mb-5">New order from John</p>
                        <span className="fs-12 mb-20 d-block text-muted"><IntlMessages id="widgets.AcceptorrRejectWithin" /></span>
                        <h1 className="border py-5 px-15 d-inline-block mr-20"> <CountDown time={500000} /> </h1>
                        <div className="d-inline-block">
                            <IconButton className="mr-10" aria-label="check">
                                <i className="zmdi zmdi-check"></i>
                            </IconButton>
                            <IconButton aria-label="close">
                                <i className="zmdi zmdi-close"></i>
                            </IconButton>
                        </div>
                    </div>
                </CardBody>
            </Card>
        );
    }
}
