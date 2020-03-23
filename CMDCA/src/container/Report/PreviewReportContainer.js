import React, { Component, Fragment } from 'react';
import PreviewReport from 'Components/Report/PreviewReport';
import RctSectionLoader from 'Components/RctSectionLoader/RctSectionLoader';
import { connect } from 'react-redux';
import { getReportById, getInstitutionById } from 'Actions';


class PreviewReportContainer extends Component{

    constructor(props){
        super(props);
        this.state = {
            loading: true
        }
    }

    componentDidMount = async () =>{
        const institution = localStorage.getItem('institution');
        await this.props.getInstitutionById(institution);
        await this.props.getReportById(this.props.match.params.id);
    }

    render(){
        const { institution, report} = this.props;
        return(
            <Fragment>
                {
                    report.loading || institution.loading ?
                    (
                        <RctSectionLoader />
                    )
                    :
                    (
                        <PreviewReport report={report.report} institution={institution.institution} />
                    )
                }
            </Fragment>
        );
    }
}

const mapStateToProps = ({ institution, report }) => {
    return {
        institution: institution,
        report: report
    };
 };

export default connect(mapStateToProps, {
    getReportById,
    getInstitutionById
 })(PreviewReportContainer)