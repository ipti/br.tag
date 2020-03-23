import React from 'react';
import { Redirect, Route, Switch } from 'react-router-dom';

import {
    AsyncFinancesContainer,
    AsyncFinancesFormContainer,
    AsyncFinancesFormEditContainer,
    
} from 'Components/AsyncComponent/AsyncComponent';


const Finances = ({ match }) => (
    <div className="content-wrapper">
        <Switch>
            <Redirect exact from={`${match.url}/`} to={`${match.url}/list`} />
            <Route path={`${match.url}/list`} component={AsyncFinancesContainer} />
            <Route exact path={`${match.url}/form`} component={AsyncFinancesFormContainer} />
            <Route exact path={`${match.url}/form/:financeID`} component={AsyncFinancesFormEditContainer} />
        </Switch>
    </div>
);

export default Finances;