import React from 'react';
import { Redirect, Route, Switch } from 'react-router-dom';

import {
    AsyncNewsContainer,
    AsyncNewsFormContainer,
    AsyncNewsFormEditContainer,
    
} from 'Components/AsyncComponent/AsyncComponent';

//const AsyncResolutionFormContainerUpdate = (props) => <AsyncResolutionFormContainer scenario="update" {...props} />


const News = ({ match }) => (
    <div className="content-wrapper">
        <Switch>
            <Redirect exact from={`${match.url}/`} to={`${match.url}/list`} />
            <Route path={`${match.url}/list`} component={AsyncNewsContainer} />
            <Route exact path={`${match.url}/form`} component={AsyncNewsFormContainer} />
            <Route exact path={`${match.url}/form/:newsID`} component={AsyncNewsFormEditContainer} />
        </Switch>
    </div>
);

export default News;