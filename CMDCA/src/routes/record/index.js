import React from 'react';
import { Redirect, Route, Switch } from 'react-router-dom';

import {
    AsyncRecordFormContainer,
	AsyncRecordContainer,
	AsyncRecordFormEditContainer,
} from 'Components/AsyncComponent/AsyncComponent';

//const AsyncReportFormContainerUpdate = (props) => <AsyncReportFormContainer scenario="update" {...props} />


const Record = ({match}) => (
    <div className="content-wrapper">
        <Switch>
            <Redirect exact from={`${match.url}/`} to={`${match.url}/list`} />
            <Route path={`${match.url}/list`} component={AsyncRecordContainer} />
            <Route exact path={`${match.url}/form`} component={AsyncRecordFormContainer} />
            <Route exact path={`${match.url}/form/:recordID`} component={AsyncRecordFormEditContainer} />
        </Switch>
    </div>
);

export default Record;
