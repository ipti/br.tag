import React, { Component, Fragment } from 'react';
import PreviewFood from 'Components/Food/PreviewFood';
import RctSectionLoader from 'Components/RctSectionLoader/RctSectionLoader';
import { connect } from 'react-redux';
import { getFoodById, getInstitutionById } from 'Actions';


class PreviewFoodContainer extends Component{

    constructor(props){
        super(props);
        this.state = {
            loading: true
        }
    }

    componentDidMount = async () =>{
        const institution = localStorage.getItem('institution');
        await this.props.getInstitutionById(institution);
        await this.props.getFoodById(this.props.match.params.id);
    }
    
    render(){
        const {food, institution} = this.props;
        return(
            <Fragment>
                {
                    food.loading || institution.loading ? 
                    (
                        <RctSectionLoader />
                    )
                    :
                    (
                        <PreviewFood food={food.food} institution={institution.institution} />
                    )
                }
            </Fragment>
        );
    }
}

const mapStateToProps = ({ food, institution }) => {
    return {
        food: food,
        institution: institution
    };
 };

export default connect(mapStateToProps, {
    getFoodById,
    getInstitutionById
 })(PreviewFoodContainer)