import React from 'react';
import { Route, Switch } from 'react-router-dom';

import {
    AsyncPreviewNotificationContainer,
    AsyncPreviewFoodContainer,
} from 'Components/AsyncComponent/AsyncComponent';

import 'Assets/css/preview/style.css';

const Preview = ({match}) => (
    <div className="page">
        <Switch>
            <Route exact={true} path={`${match.url}/notification/:id`} component={AsyncPreviewNotificationContainer} />
            <Route exact={true} path={`${match.url}/food/:id`} component={AsyncPreviewFoodContainer} />
        </Switch>
    </div>
);

export default Preview;
