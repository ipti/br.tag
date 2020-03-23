import React from 'react';
import { Redirect, Route, Switch } from 'react-router-dom';

import {
    AsyncScheduleContainer,
    AsyncScheduleFormContainer,
    AsyncScheduleFormEditContainer,
} from 'Components/AsyncComponent/AsyncComponent';

//const AsyncResolutionFormContainerUpdate = (props) => <AsyncResolutionFormContainer scenario="update" {...props} />


const Advisor = ({ match }) => (
    <div className="content-wrapper">
        <Switch>
            <Redirect exact from={`${match.url}/`} to={`${match.url}/list`} />
            <Route path={`${match.url}/list`} component={AsyncScheduleContainer} />
            <Route exact path={`${match.url}/form`} component={AsyncScheduleFormContainer} />
            <Route exact path={`${match.url}/form/:scheduleID`} component={AsyncScheduleFormEditContainer} />
        </Switch>
    </div>
);

export default Advisor;