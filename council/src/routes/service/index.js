import React from 'react';
import { Redirect, Route, Switch } from 'react-router-dom';

import {
    AsyncNotificationContainer,
    AsyncServiceFormContainer
} from 'Components/AsyncComponent/AsyncComponent';

const AsyncServiceFormContainerUpdate = (props) => <AsyncServiceFormContainer scenario="update" {...props} />


const Service = ({match}) => (
    <div className="content-wrapper">
        <Switch>
            <Redirect exact from={`${match.url}/`} to={`${match.url}/list`} />
            <Route path={`${match.url}/list`} component={AsyncNotificationContainer} />
            <Route exact path={`${match.url}/form`} component={AsyncServiceFormContainer} />
            <Route exact path={`${match.url}/form/:id`} component={AsyncServiceFormContainerUpdate} />
        </Switch>
    </div>
);

export default Service;
