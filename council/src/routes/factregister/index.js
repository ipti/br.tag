/* 
 * FactRegister
 */

import React from 'react';
import { Redirect, Route, Switch, Link } from 'react-router-dom';

// async components
import {
    
    AsyncComplaintFactRegisterFormComponent,
    AsyncComplaintFactRegisterComponent
} from 'Components/AsyncComponent/AsyncComponent';


const FactRegister = ({match}) => (
    <div className="citizen-wrapper" style={{overflowY: 'auto', height: "100%"}}>
    <Switch>
        <Redirect exact from={`${match.url}/`} to={`${match.url}/form`} />
        <Route path={`${match.url}/form`} component={AsyncComplaintFactRegisterFormComponent} />
    </Switch>
    </div>
);

export default FactRegister;