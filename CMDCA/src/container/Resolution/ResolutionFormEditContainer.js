import React, { Component, Fragment } from 'react';
import ResolutionFormEdit from 'Components/Resolution/ResolutionFormEdit';

export default class NewsFormContainerEdit extends Component{
    
  
    render(){
        return(
            <Fragment>
                <ResolutionFormEdit {...this.props}/>
            </Fragment>    
        );
    }

}