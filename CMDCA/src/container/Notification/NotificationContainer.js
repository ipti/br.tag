import React, { Component, Fragment } from 'react';
import Notification from 'Components/Notification/Notification';
import CustomPagination from 'Components/CustomPagination/CustomPagination';
import Card from 'Components/Notification/Card';
import RctSectionLoader from 'Components/RctSectionLoader/RctSectionLoader';
import { Alert } from 'reactstrap';
import { connect } from 'react-redux';
import { getNotification } from 'Actions';
import 'Assets/css/notification/style.css';


class NotificationContainer extends Component{

    constructor(props){
        super(props);
        this.state = {
            notifications: [],
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
        await this.props.getNotification(nextPage);
    }

    componentDidMount = async () =>{
        await this.props.getNotification();
    }

    loadNotification = () =>{
        return this.props.notifications.map((notification) =>{
            return <Card id={notification._id} notified={notification.notified.name} createdAt={notification.createdAt} />
        });
    }
    
    render(){
        const notifications = this.loadNotification();
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
                            <Notification notifications={notifications} />
                            {
                                notifications.length > 0 ?
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

const mapStateToProps = ({ notification }) => {
    return notification;
 };

export default connect(mapStateToProps, {
    getNotification
 })(NotificationContainer)