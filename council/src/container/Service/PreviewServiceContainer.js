import React, { Component, Fragment } from 'react';
import PreviewService from 'Components/Service/PreviewService';
import RctSectionLoader from 'Components/RctSectionLoader/RctSectionLoader';
import { connect } from 'react-redux';
import { getServiceById, getInstitutionById } from 'Actions';


class PreviewServiceContainer extends Component{

    constructor(props){
        super(props);
        this.state = {
            loading: true
        }
    }

    componentDidMount = async () =>{
        const institution = localStorage.getItem('institution');
        await this.props.getInstitutionById(institution);
        await this.props.getServiceById(this.props.match.params.id);
    }

    render(){
        const { institution, service} = this.props;
        return(
            <Fragment>
                {
                    service.loading && institution.loading ?
                    (
                        <RctSectionLoader />
                    )
                    :
                    (
                        <PreviewService service={service.service} institution={institution.institution} />
                    )
                }
            </Fragment>
        );
    }
}

const mapStateToProps = ({ institution, service }) => {
    return {
        institution: institution,
        service: service
    };
 };

export default connect(mapStateToProps, {
    getServiceById,
    getInstitutionById
 })(PreviewServiceContainer)