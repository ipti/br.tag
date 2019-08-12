import React from 'react';
import { Redirect, Route, Switch } from 'react-router-dom';

import {
    AsyncAttendanceContainer,
    AsyncAttendanceFormContainer
} from 'Components/AsyncComponent/AsyncComponent';

const Attendance = ({match}) => (
    <div className="content-wrapper">
        <Switch>
            <Redirect exact from={`${match.url}/`} to={`${match.url}/list`} />
            <Route path={`${match.url}/list`} component={AsyncAttendanceContainer} />
            <Route exact path={`${match.url}/form`} component={AsyncAttendanceFormContainer} />
        </Switch>
    </div>
);

export default Attendance;
