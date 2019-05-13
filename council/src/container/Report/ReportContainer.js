import React, { Component, Fragment } from 'react';
import Report from 'Components/Report/Report';
import CustomPagination from 'Components/CustomPagination/CustomPagination';
import Card from 'Components/Report/Card';
import RctSectionLoader from 'Components/RctSectionLoader/RctSectionLoader';
import { Alert } from 'reactstrap';
import { connect } from 'react-redux';
import { getReport } from 'Actions';
import 'Assets/css/notification/style.css';


class ReportContainer extends Component{

    constructor(props){
        super(props);
        this.state = {
            reports: [],
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
        await this.props.getReport(nextPage);
    }

    componentDidMount = async () =>{
        await this.props.getReport();
    }

    loadReport = () =>{
        return this.props.reports.map((notification) =>{
            return <Card id={notification._id} notified={notification.notified.name} createdAt={notification.createdAt} />
        });
    }
    
    render(){
        const reports = this.loadReport();
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
                            <Report reports={reports} />
                            {
                                reports.length > 0 ?
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

const mapStateToProps = ({ report }) => {
    return report;
 };

export default connect(mapStateToProps, {
    getReport
 })(ReportContainer)