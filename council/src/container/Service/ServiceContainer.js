import React, { Component, Fragment } from 'react';
import Service from 'Components/Service/Service';
import CustomPagination from 'Components/CustomPagination/CustomPagination';
import Card from 'Components/Service/Card';
import RctSectionLoader from 'Components/RctSectionLoader/RctSectionLoader';
import { Alert } from 'reactstrap';
import { connect } from 'react-redux';
import { getService } from 'Actions';
import 'Assets/css/service/style.css';


class ServiceContainer extends Component{

    constructor(props){
        super(props);
        this.state = {
            services: [],
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
        await this.props.getService(nextPage);
    }

    componentDidMount = async () =>{
        await this.props.getService();
    }

    loadService = () =>{
        return this.props.services.map((service) =>{
            return <Card id={service._id} child={service.child.name} createdAt={service.createdAt} />
        });
    }
    
    render(){
        const services = this.loadService();
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
                            <Service services={services} />
                            {
                                services.length > 0 ?
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

const mapStateToProps = ({ service }) => {
    return service;
 };

export default connect(mapStateToProps, {
    getService
 })(ServiceContainer)