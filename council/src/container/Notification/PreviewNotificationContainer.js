import React, { Component, Fragment } from 'react';
import PreviewNotification from 'Components/Notification/PreviewNotification';
import RctSectionLoader from 'Components/RctSectionLoader/RctSectionLoader';
import { connect } from 'react-redux';
import { getNotificationById, getInstitutionById } from 'Actions';


class PreviewNotificationContainer extends Component{

    constructor(props){
        super(props);
        this.state = {
            loading: true
        }
    }

    componentDidMount = async () =>{
        const institution = localStorage.getItem('institution');
        await this.props.getInstitutionById(institution);
        await this.props.getNotificationById(this.props.match.params.id);
    }
    
    render(){
        const {notification, institution} = this.props;
        
        return(
            <Fragment>
                {
                    notification.loading && institution.loading ? 
                    (
                        <RctSectionLoader />
                    )
                    :
                    (
                        <PreviewNotification notification={notification.notification} institution={institution.institution} />
                    )
                }
            </Fragment>
        );
    }
}

const mapStateToProps = ({ notification, institution }) => {
    return {
        notification: notification,
        institution: institution
    };
 };

export default connect(mapStateToProps, {
    getNotificationById,
    getInstitutionById
 })(PreviewNotificationContainer)