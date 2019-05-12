import React from 'react';
import { Redirect, Route, Switch } from 'react-router-dom';

import {
    AsyncFactContainer,
    AsyncFactFormContainer
} from 'Components/AsyncComponent/AsyncComponent';

const AsyncFactFormContainerUpdate = (props) => <AsyncFactFormContainer scenario="update" {...props} />


const Fact = ({match}) => (
    <div className="content-wrapper">
        <Switch>
            <Redirect exact from={`${match.url}/`} to={`${match.url}/list`} />
            <Route path={`${match.url}/list`} component={AsyncFactContainer} />
            <Route exact path={`${match.url}/form`} component={AsyncFactFormContainer} />
            <Route exact path={`${match.url}/form/:id`} component={AsyncFactFormContainerUpdate} />
        </Switch>
    </div>
);

export default Fact;
