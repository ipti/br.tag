/**
 * Booking Info Widget
 */
import React, { Component } from 'react';
import { Card, CardTitle } from 'reactstrap';
import List from '@material-ui/core/List';
import ListItem from '@material-ui/core/ListItem';
import Button from '@material-ui/core/Button';

// intl messages
import IntlMessages from 'Util/IntlMessages';

export default class BookingInfo extends Component {
    render() {
        return (
            <Card className="rct-block">
                <List className="p-0 fs-14">
                    <ListItem className="d-flex justify-content-between border-bottom  align-items-center p-15">
                        <span>
                            <i className="material-icons mr-25 fs-14 text-primary">add_to_photos</i>
                            <IntlMessages id="widgets.booking" />
                        </span>
                        <span>1450</span>
                    </ListItem>
                    <ListItem className="d-flex justify-content-between align-items-center border-bottom p-15">
                        <span>
                            <i className="material-icons mr-25 text-success fs-14">check_box</i>
                            <IntlMessages id="widgets.confirmed" />
                        </span>
                        <span>1000</span>
                    </ListItem>
                    <ListItem className="d-flex justify-content-between align-items-center p-15">
                        <span>
                            <i className="material-icons mr-25 text-danger fs-14">access_time</i>
                            <IntlMessages id="widgets.pending" />
                        </span>
                        <span>450</span>
                    </ListItem>
                </List>
            </Card>
        );
    }
}
