import React, {Component, Fragment} from 'react';
import FinancesList from 'Components/Finances/FinancesList';

export default class FinancesContainer extends Component{
    render(){
        return(
            <Fragment>
                <FinancesList/>
            </Fragment>
        );
    }
}