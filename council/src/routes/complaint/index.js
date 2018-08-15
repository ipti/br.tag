/**
 * Complaint
 */

import React from 'react';
import { Redirect, Route, Switch } from 'react-router-dom';

// async components
import {
    AsyncComplaintListComponent,
    AsyncComplaintInsertComponent,
    AsyncComplaintViewComponent
} from 'Components/AsyncComponent/AsyncComponent';


const Complaint = ({match}) => (
    <div className="complaint-wrapper">
    <Switch>
        <Redirect exact from={`${match.url}/`} to={`${match.url}/list`} />
        <Route path={`${match.url}/list`} component={AsyncComplaintListComponent} />
        <Route path={`${match.url}/insert`} component={AsyncComplaintInsertComponent} />
        <Route path={`${match.url}/view`} component={AsyncComplaintViewComponent} />
    </Switch>
    </div>
);

export default Complaint;
