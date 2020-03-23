import React, { Component, Fragment } from 'react';
import Housing from 'Components/Housing/Housing';
import CustomPagination from 'Components/CustomPagination/CustomPagination';
import Card from 'Components/Housing/Card';
import RctSectionLoader from 'Components/RctSectionLoader/RctSectionLoader';
import { Alert } from 'reactstrap';
import { connect } from 'react-redux';
import { getHousing } from 'Actions';
import 'Assets/css/housing/style.css';


class HousingContainer extends Component{

    constructor(props){
        super(props);
        this.state = {
            housings: [],
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
        await this.props.getHousing(nextPage);
    }

    componentDidMount = async () =>{
        await this.props.getHousing();
    }

    loadHousing = () =>{
        return this.props.housings.map((housing) =>{
            return <Card id={housing._id} child={housing.child.name} createdAt={housing.createdAt} />
        });
    }
    
    render(){
        const housings = this.loadHousing();
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
                            <Housing housings={housings} />
                            {
                                housings.length > 0 ?
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

const mapStateToProps = ({ housing }) => {
    return housing;
 };

export default connect(mapStateToProps, {
    getHousing
 })(HousingContainer)