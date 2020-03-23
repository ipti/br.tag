import React from 'react';
import { Redirect, Route, Switch } from 'react-router-dom';

import {
    AsyncReportContainer,
    AsyncReportFormContainer
} from 'Components/AsyncComponent/AsyncComponent';

const AsyncReportFormContainerUpdate = (props) => <AsyncReportFormContainer scenario="update" {...props} />


const Report = ({match}) => (
    <div className="content-wrapper">
        <Switch>
            <Redirect exact from={`${match.url}/`} to={`${match.url}/list`} />
            <Route path={`${match.url}/list`} component={AsyncReportContainer} />
            <Route exact path={`${match.url}/form`} component={AsyncReportFormContainer} />
            <Route exact path={`${match.url}/form/:id`} component={AsyncReportFormContainerUpdate} />
        </Switch>
    </div>
);

export default Report;
