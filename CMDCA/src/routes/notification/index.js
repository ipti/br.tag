import React from 'react';
import { Redirect, Route, Switch } from 'react-router-dom';

import {
    AsyncNotificationContainer,
    AsyncNotificationFormContainer
} from 'Components/AsyncComponent/AsyncComponent';

const AsyncNotificationFormContainerUpdate = (props) => <AsyncNotificationFormContainer scenario="update" {...props} />


const Notification = ({match}) => (
    <div className="content-wrapper">
        <Switch>
            <Redirect exact from={`${match.url}/`} to={`${match.url}/list`} />
            <Route path={`${match.url}/list`} component={AsyncNotificationContainer} />
            <Route exact path={`${match.url}/form`} component={AsyncNotificationFormContainer} />
            <Route exact path={`${match.url}/form/:id`} component={AsyncNotificationFormContainerUpdate} />
        </Switch>
    </div>
);

export default Notification;
