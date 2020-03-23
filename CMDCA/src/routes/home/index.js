import React from 'react';
import { Redirect, Route, Switch } from 'react-router-dom';

import {
    AsyncHomeContainer
} from 'Components/AsyncComponent/AsyncComponent';

const Home = ({ match }) => (
    <div className="content-wrapper">
        <Switch>
            <Redirect exact from={`${match.url}/`} to={`${match.url}/index`} />
            <Route path={`${match.url}/index`} component={AsyncHomeContainer} />
        </Switch>
    </div>
);

export default Home;