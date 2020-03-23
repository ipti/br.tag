import React, {Component, Fragment} from 'react';
import ScheduleList from 'Components/Schedule/ScheduleList';

export default class ScheduleContainer extends Component{
    render(){
        return(
            <Fragment>
                <ScheduleList/>
            </Fragment>
        );
    }
}