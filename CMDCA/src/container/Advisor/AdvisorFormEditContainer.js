import React, { Component, Fragment } from 'react';
import AdvisorFormEdit from 'Components/Advisor/AdvisorFormEdit';

export default class AdvisorFormEditContainer extends Component{
    
  
    render(){
        return(
            <Fragment>
                <AdvisorFormEdit {...this.props}/>
            </Fragment>    
        );
    }

}