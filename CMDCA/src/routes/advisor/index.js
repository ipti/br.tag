import React from 'react';
import { Redirect, Route, Switch } from 'react-router-dom';

import {
    AsyncAdvisorContainer,
    AsyncAdvisorFormContainer,
    AsyncAdvisorFormEditContainer,
    
} from 'Components/AsyncComponent/AsyncComponent';

//const AsyncResolutionFormContainerUpdate = (props) => <AsyncResolutionFormContainer scenario="update" {...props} />


const Advisor = ({ match }) => (
    <div className="content-wrapper">
        <Switch>
            <Redirect exact from={`${match.url}/`} to={`${match.url}/list`} />
            <Route path={`${match.url}/list`} component={AsyncAdvisorContainer} />
            <Route exact path={`${match.url}/form`} component={AsyncAdvisorFormContainer} />
            <Route exact path={`${match.url}/form/:advisorID`} component={AsyncAdvisorFormEditContainer} />
        </Switch>
    </div>
);

export default Advisor;