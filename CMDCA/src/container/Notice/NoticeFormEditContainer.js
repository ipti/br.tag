import React, { Component, Fragment } from 'react';
import NoticeFormEdit from 'Components/Notice/NoticeFormEdit';

export default class NoticeFormContainer extends Component{
    
  
    render(){
        return(
            <Fragment>
                <NoticeFormEdit {...this.props}/>
            </Fragment>    
        );
    }

}