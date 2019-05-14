import React, { Component, Fragment } from 'react';
import PreviewHousing from 'Components/Housing/PreviewHousing';
import RctSectionLoader from 'Components/RctSectionLoader/RctSectionLoader';
import { connect } from 'react-redux';
import { getHousingById, getInstitutionById } from 'Actions';


class PreviewHousingContainer extends Component{

    constructor(props){
        super(props);
        this.state = {
            loading: true
        }
    }

    componentDidMount = async () =>{
        const institution = localStorage.getItem('institution');
        await this.props.getInstitutionById(institution);
        await this.props.getHousingById(this.props.match.params.id);
    }
    
    render(){
        const {housing, institution} = this.props;
        
        return(
            <Fragment>
                {
                    housing.loading || institution.loading ? 
                    (
                        <RctSectionLoader />
                    )
                    :
                    (
                        <PreviewHousing housing={housing.housing} institution={institution.institution} />
                    )
                }
            </Fragment>
        );
    }
}

const mapStateToProps = ({ housing, institution }) => {
    return {
        housing: housing,
        institution: institution
    };
 };

export default connect(mapStateToProps, {
    getHousingById,
    getInstitutionById
 })(PreviewHousingContainer)