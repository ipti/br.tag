import React, { Component, Fragment } from 'react';
import PreviewWarning from 'Components/Warning/PreviewWarning';
import RctSectionLoader from 'Components/RctSectionLoader/RctSectionLoader';
import { connect } from 'react-redux';
import { getWarningById, getInstitutionById } from 'Actions';


class PreviewWarningContainer extends Component{

    constructor(props){
        super(props);
        this.state = {
            loading: true
        }
    }

    componentDidMount = async () =>{
        const institution = localStorage.getItem('institution');
        await this.props.getInstitutionById(institution);
        await this.props.getWarningById(this.props.match.params.id);
    }
    
    render(){
        const {warning, institution} = this.props;
        return(
            <Fragment>
                {
                    warning.loading && institution.loading ? 
                    (
                        <RctSectionLoader />
                    )
                    :
                    (
                        <PreviewWarning warning={warning.warning} institution={institution.institution} />
                    )
                }
            </Fragment>
        );
    }
}

const mapStateToProps = ({ warning, institution }) => {
    return {
        warning: warning,
        institution: institution
    };
 };

export default connect(mapStateToProps, {
    getWarningById,
    getInstitutionById
 })(PreviewWarningContainer)