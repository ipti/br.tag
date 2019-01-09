/**
 * Complaint
 */

import React from 'react';
import { Redirect, Route, Switch } from 'react-router-dom';

// async components
import {
    AsyncComplaintListComponent,
    AsyncComplaintListReceiveComponent,
    AsyncComplaintListAnalisysComponent,
    AsyncComplaintListForwardComponent,
    AsyncComplaintListCompletedComponent,
    AsyncComplaintInsertComponent,
    AsyncComplaintFormalizeComponent,
    AsyncComplaintUpdateComponent,
    AsyncComplaintViewComponent
} from 'Components/AsyncComponent/AsyncComponent';


const Complaint = ({match}) => (
    <div className="complaint-wrapper">
    <Switch>
        <Redirect exact from={`${match.url}/`} to={`${match.url}/list`} />
        <Route path={`${match.url}/list`} component={AsyncComplaintListComponent} />
        <Route path={`${match.url}/receive`} component={AsyncComplaintListReceiveComponent} />
        <Route path={`${match.url}/analysis`} component={AsyncComplaintListAnalisysComponent} />
        <Route path={`${match.url}/forward`} component={AsyncComplaintListForwardComponent} />
        <Route path={`${match.url}/completed`} component={AsyncComplaintListCompletedComponent} />
        <Route path={`${match.url}/insert`} component={AsyncComplaintInsertComponent} />
        <Route path={`${match.url}/view/:id`} component={AsyncComplaintViewComponent} />
        <Route path={`${match.url}/formalize/:id`} component={AsyncComplaintFormalizeComponent} />
        <Route path={`${match.url}/update/:id`} component={AsyncComplaintUpdateComponent} />
    </Switch>
    </div>
);

export default Complaint;
