import React, {Component, Fragment} from 'react';
import NewsList from 'Components/News/NewsList';

export default class NewsContainer extends Component{
    render(){
        return(
            <Fragment>
                <NewsList/>
            </Fragment>
        );
    }
}