import React, {Component, Fragment} from 'react';
import RecordList from 'Components/Record/RecordList';

export default class RecordContainer extends Component{
    render(){
        return(
            <Fragment>
                <RecordList/>
            </Fragment>
        );
    }
}