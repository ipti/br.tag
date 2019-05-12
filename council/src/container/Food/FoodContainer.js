import React, { Component, Fragment } from 'react';
import Food from 'Components/Food/Food';
import CustomPagination from 'Components/CustomPagination/CustomPagination';
import Card from 'Components/Food/Card';
import RctSectionLoader from 'Components/RctSectionLoader/RctSectionLoader';
import { Alert } from 'reactstrap';
import { connect } from 'react-redux';
import { getFood } from 'Actions';
import 'Assets/css/food/style.css';


class FoodContainer extends Component{

    constructor(props){
        super(props);
        this.state = {
            foods: [],
            pagination: {
                currentPage: 1,
                perPage: 15,
                totalItens: 0,
                totalPages: 1
            },
            loading: true
        }
    }

    pageChange = async (page) =>{
        const nextPage = page.selected + 1;
        await this.props.getFood(nextPage);
    }

    componentDidMount = async () =>{
        await this.props.getFood();
    }

    loadFoods = () =>{
        return this.props.foods.map((food) =>{
            return (
                <Card id={food._id} personApplicantName={food.personApplicant.name} reason={food.reason} createdAt={food.createdAt} />
            );
        });
    }
    
    render(){
        const foods = this.loadFoods();
        return(
            <Fragment>
                {
                    this.props.loading ? 
                    (
                        <RctSectionLoader />
                    )
                    :
                    (
                        <Fragment>
                            <Food foods={foods} />
                            {
                                foods.length > 0 ?
                                (
                                    this.props.pagination.totalPages > 1 && <CustomPagination totalItens={this.props.pagination.totalItens} perPage={this.props.pagination.perPage} totalPages={this.props.pagination.totalPages} currentPage={this.props.pagination.currentPage} handlePageChange={this.pageChange} />
                                )
                                :
                                (
                                    <Alert color="danger"> Nenhum item para exibir </Alert>
                                )
                            }
                        </Fragment>
                    )
                }
            </Fragment>
        );
    }

}

const mapStateToProps = ({ food }) => {
    return food;
};

export default connect(mapStateToProps, {
    getFood
 })(FoodContainer)