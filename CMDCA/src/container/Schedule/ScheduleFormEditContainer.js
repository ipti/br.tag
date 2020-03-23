import React, { Component, Fragment } from 'react';
import ScheduleFormEdit from 'Components/Schedule/ScheduleFormEdit';

export default class ScheduleFormContainer extends Component{
    
  
    render(){
        return(
            <Fragment>
                <ScheduleFormEdit {...this.props}/>
            </Fragment>    
        );
    }

}