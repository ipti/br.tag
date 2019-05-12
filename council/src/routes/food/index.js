import React from 'react';
import { Redirect, Route, Switch } from 'react-router-dom';

import {
    AsyncFoodContainer,
    AsyncFoodFormContainer
} from 'Components/AsyncComponent/AsyncComponent';

const AsyncFoodFormContainerUpdate = (props) => <AsyncFoodFormContainer scenario="update" {...props} />


const Food = ({ match }) => (
    <div className="content-wrapper">
        <Switch>
            <Redirect exact from={`${match.url}/`} to={`${match.url}/list`} />
            <Route path={`${match.url}/list`} component={AsyncFoodContainer} />
            <Route exact path={`${match.url}/form`} component={AsyncFoodFormContainer} />
            <Route exact path={`${match.url}/form/:id`} component={AsyncFoodFormContainerUpdate} />
        </Switch>
    </div>
);

export default Food;
