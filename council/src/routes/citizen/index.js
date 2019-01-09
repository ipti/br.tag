/* 
 * Citizen
 */

import React from 'react';
import { Redirect, Route, Switch, Link } from 'react-router-dom';

// async components
import {
    AsyncCitizenFollowComponent,
    AsyncCitizenViewerComponent,
    AsyncCitizenFormComponent,
    AsyncComplaintFactRegisterComponent
} from 'Components/AsyncComponent/AsyncComponent';


const Citizen = ({match}) => (
    <div className="citizen-wrapper" style={{overflowY: 'auto', height: "100%"}}>
    <Switch>
        <Redirect exact from={`${match.url}/`} to={`${match.url}/follow`} />
        <Route path={`${match.url}/follow`} component={AsyncCitizenFollowComponent} />
        <Route path={`${match.url}/view/:id`} component={AsyncCitizenViewerComponent} />
        <Route path={`${match.url}/form`} component={AsyncCitizenFormComponent} />
        <Route path={`${match.url}/fact`} component={AsyncComplaintFactRegisterComponent} />
    </Switch>
    </div>
);

export default Citizen;
