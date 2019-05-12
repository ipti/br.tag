import React, { Component, Fragment } from 'react';
import PreviewFact from 'Components/Fact/PreviewFact';
import RctSectionLoader from 'Components/RctSectionLoader/RctSectionLoader';
import { connect } from 'react-redux';
import { getFactById, getInstitutionById } from 'Actions';


class PreviewFactContainer extends Component{

    constructor(props){
        super(props);
        this.state = {
            loading: true
        }
    }

    componentDidMount = async () =>{
        const institution = localStorage.getItem('institution');
        await this.props.getInstitutionById(institution);
        await this.props.getFactById(this.props.match.params.id);
    }
    
    render(){
        const {fact, institution} = this.props;
        
        return(
            <Fragment>
                {
                    fact.loading && institution.loading ? 
                    (
                        <RctSectionLoader />
                    )
                    :
                    (
                        <PreviewFact fact={fact.fact} institution={institution.institution} />
                    )
                }
            </Fragment>
        );
    }
}

const mapStateToProps = ({ fact, institution }) => {
    return {
        fact: fact,
        institution: institution
    };
 };

export default connect(mapStateToProps, {
    getFactById,
    getInstitutionById
 })(PreviewFactContainer)
