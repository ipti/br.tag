import React, {Component, Fragment} from 'react';
import NoticeList from 'Components/Notice/NoticeList';

export default class NoticeContainer extends Component{
    render(){
        return(
            <Fragment>
                <NoticeList/>
            </Fragment>
        );
    }
}