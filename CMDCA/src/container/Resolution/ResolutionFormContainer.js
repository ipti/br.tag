import React, { Component, Fragment } from 'react';
import ResolutionForm from 'Components/Resolution/ResolutionForm';
import 'Assets/css/resolution/style.css';


class ResolutionFormContainer extends Component{

  
    render(){
        return(
            <Fragment>
                <ResolutionForm  />
            </Fragment>    
        );
    }

}



export default ResolutionFormContainer;