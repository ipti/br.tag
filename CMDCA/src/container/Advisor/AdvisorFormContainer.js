import React, { Component, Fragment } from 'react';
import AdvisorForm from 'Components/Advisor/AdvisorForm';

class AdvisorFormContainer extends Component{
    render(){
        return(
            <Fragment>
                <AdvisorForm />
            </Fragment>    
        );
    }
}

export default AdvisorFormContainer;