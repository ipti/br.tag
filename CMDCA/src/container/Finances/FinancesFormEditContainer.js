import React, { Component, Fragment } from 'react';
import FinancesFormEdit from 'Components/Finances/FinancesFormEdit';

export default class FinancesFormContainer extends Component{
    
  
    render(){
        return(
            <Fragment>
                <FinancesFormEdit {...this.props}/>
            </Fragment>    
        );
    }

}