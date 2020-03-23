import React from 'react';
import { Redirect, Route, Switch } from 'react-router-dom';

import {
    AsyncWarningContainer,
    AsyncWarningFormContainer
} from 'Components/AsyncComponent/AsyncComponent';

const AsyncWarningFormContainerUpdate = (props) => <AsyncWarningFormContainer scenario="update" {...props} />


const Warning = ({ match }) => (
    <div className="content-wrapper">
        <Switch>
            <Redirect exact from={`${match.url}/`} to={`${match.url}/list`} />
            <Route path={`${match.url}/list`} component={AsyncWarningContainer} />
            <Route exact path={`${match.url}/form`} component={AsyncWarningFormContainer} />
            <Route exact path={`${match.url}/form/:id`} component={AsyncWarningFormContainerUpdate} />
        </Switch>
    </div>
);

export default Warning;
