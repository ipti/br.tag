import React, { Component, Fragment } from 'react';
import Warning from 'Components/Warning/Warning';
import CustomPagination from 'Components/CustomPagination/CustomPagination';
import Card from 'Components/Warning/Card';
import RctSectionLoader from 'Components/RctSectionLoader/RctSectionLoader';
import { Alert } from 'reactstrap';
import { connect } from 'react-redux';
import { getWarning } from 'Actions';
import 'Assets/css/warning/style.css';


class WarningContainer extends Component{

    constructor(props){
        super(props);
        this.state = {
            warnings: [],
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
        await this.props.getWarning(nextPage);
    }

    componentDidMount = async () =>{
        await this.props.getWarning();
    }

    loadWarnings = () =>{
        return this.props.warnings.map((warning) =>{
            return (
                <Card id={warning._id} personAdolescentName={warning.personAdolescent.name} reason={warning.reason} createdAt={warning.createdAt} />
            );
        });
    }
    
    render(){
        const warnings = this.loadWarnings();
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
                            <Warning warnings={warnings} />
                            {
                                warnings.length > 0 ?
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

const mapStateToProps = ({ warning }) => {
    return warning;
};

export default connect(mapStateToProps, {
    getWarning
 })(WarningContainer)