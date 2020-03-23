import React from 'react';
import { Redirect, Route, Switch } from 'react-router-dom';

import {
    AsyncPeopleContainer,
    AsyncPeopleFormContainer
} from 'Components/AsyncComponent/AsyncComponent';

const AsyncPeopleFormContainerUpdate = (props) => <AsyncPeopleFormContainer scenario="update" {...props} />


const People = ({match}) => (
    <div className="content-wrapper">
        <Switch>
            <Redirect exact from={`${match.url}/`} to={`${match.url}/list`} />
            <Route path={`${match.url}/list`} component={AsyncPeopleContainer} />
            <Route exact path={`${match.url}/form`} component={AsyncPeopleFormContainer} />
            <Route exact path={`${match.url}/form/:id`} component={AsyncPeopleFormContainerUpdate} />
        </Switch>
    </div>
);

export default People;
