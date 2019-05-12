import React from 'react';
import { Redirect, Route, Switch } from 'react-router-dom';

import {
    AsyncHousingContainer,
    AsyncHousingFormContainer
} from 'Components/AsyncComponent/AsyncComponent';

const AsyncHousingFormContainerUpdate = (props) => <AsyncHousingFormContainer scenario="update" {...props} />


const Housing = ({match}) => (
    <div className="content-wrapper">
        <Switch>
            <Redirect exact from={`${match.url}/`} to={`${match.url}/list`} />
            <Route path={`${match.url}/list`} component={AsyncHousingContainer} />
            <Route exact path={`${match.url}/form`} component={AsyncHousingFormContainer} />
            <Route exact path={`${match.url}/form/:id`} component={AsyncHousingFormContainerUpdate} />
        </Switch>
    </div>
);

export default Housing;
