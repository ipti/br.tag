import React, { Component, Fragment } from 'react';
import Fact from 'Components/Fact/Fact';
import CustomPagination from 'Components/CustomPagination/CustomPagination';
import Card from 'Components/Fact/Card';
import RctSectionLoader from 'Components/RctSectionLoader/RctSectionLoader';
import { Alert } from 'reactstrap';
import { connect } from 'react-redux';
import { getFact } from 'Actions';
import 'Assets/css/fact/style.css';


class FactContainer extends Component{

    constructor(props){
        super(props);
        this.state = {
            facts: [],
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
        await this.props.getFact(nextPage);
    }

    componentDidMount = async () =>{
        await this.props.getFact();
    }

    loadFact = () =>{
        return this.props.facts.map((fact) =>{
            return <Card id={fact._id} child={fact.child.name} createdAt={fact.createdAt} />
        });
    }
    
    render(){
        const facts = this.loadFact();
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
                            <Fact facts={facts} />
                            {
                                facts.length > 0 ?
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

const mapStateToProps = ({ fact }) => {
    return fact;
 };

export default connect(mapStateToProps, {
    getFact
 })(FactContainer)
