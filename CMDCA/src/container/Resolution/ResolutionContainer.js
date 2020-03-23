import React, {Component, Fragment} from 'react';
import ResolutionList from 'Components/Resolution/ResolutionList';

export default class ResolutionContainer extends Component{
    render(){
        return(
            <Fragment>
                <ResolutionList/>
            </Fragment>
        );
    }
}