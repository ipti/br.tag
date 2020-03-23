import React, { Component, Fragment } from 'react';
import NewsForm from 'Components/News/NewsForm';

export default class NewsFormContainer extends Component{

  
    render(){
        return(
            <Fragment>
                <NewsForm />
            </Fragment>    
        );
    }

}
