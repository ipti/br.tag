import React from 'react';
import { Redirect, Route, Switch } from 'react-router-dom';

import {
    AsyncNoticeFormContainer,
	AsyncNoticeContainer,
	AsyncNoticeFormEditContainer
} from 'Components/AsyncComponent/AsyncComponent';

//const AsyncReportFormContainerUpdate = (props) => <AsyncReportFormContainer scenario="update" {...props} />


const Notice = ({match}) => (
    <div className="content-wrapper">
        <Switch>
            <Redirect exact from={`${match.url}/`} to={`${match.url}/list`} />
            <Route path={`${match.url}/list`} component={AsyncNoticeContainer} />
            <Route exact path={`${match.url}/form`} component={AsyncNoticeFormContainer} />
            <Route exact path={`${match.url}/form/:noticeID`} component={AsyncNoticeFormEditContainer} />
        </Switch>
    </div>
);

export default Notice;
