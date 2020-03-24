import React from 'react';
import { Redirect, Route, Switch } from 'react-router-dom';

import {
    AsyncResolutionContainer,
    AsyncResolutionFormEditContainer,
    AsyncResolutionFormContainer
} from 'Components/AsyncComponent/AsyncComponent';

//const AsyncResolutionFormContainerUpdate = (props) => <AsyncResolutionFormContainer scenario="update" {...props} />


const Resolution = ({ match }) => (
    <div className="content-wrapper">
        <Switch>
            <Redirect exact from={`${match.url}/`} to={`${match.url}/list`} />
            <Route path={`${match.url}/list`} component={AsyncResolutionContainer} />
            <Route exact path={`${match.url}/form`} component={AsyncResolutionFormContainer} />
            <Route exact path={`${match.url}/form/:resolutionID`} component={AsyncResolutionFormEditContainer} />
        </Switch>
    </div>
);

export default Resolution;
