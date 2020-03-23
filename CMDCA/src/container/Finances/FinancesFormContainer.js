import React, { Component, Fragment } from 'react';
import FinancesForm from 'Components/Finances/FinancesForm';

export default class FinancesFormContainer extends Component{

  
    render(){
        return(
            <Fragment>
                <FinancesForm />
            </Fragment>    
        );
    }

}
