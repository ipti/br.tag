import React, { Component, Fragment } from 'react';
import NewsFormEdit from 'Components/News/NewsFormEdit';

export default class NewsFormContainerEdit extends Component{
    
  
    render(){
        return(
            <Fragment>
                <NewsFormEdit {...this.props}/>
            </Fragment>    
        );
    }

}