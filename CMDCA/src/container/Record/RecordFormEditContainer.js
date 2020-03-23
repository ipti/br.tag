import React, { Component, Fragment } from 'react';
import RecordFormEdit from 'Components/Record/RecordFormEdit';

export default class RecordFormContainer extends Component{
    
  
    render(){
        return(
            <Fragment>
                <RecordFormEdit {...this.props}/>
            </Fragment>    
        );
    }

}