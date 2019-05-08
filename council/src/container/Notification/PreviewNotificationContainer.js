import React, { Component, Fragment } from 'react';
import PreviewNotification from 'Components/Notification/PreviewNotification';
import RctSectionLoader from 'Components/RctSectionLoader/RctSectionLoader';
import { connect } from 'react-redux';
import { getNotificationById } from 'Actions';


class PreviewNotificationContainer extends Component{

    constructor(props){
        super(props);
        this.state = {
            loading: true
        }
    }

    componentDidMount = async () =>{
        await this.props.getNotificationById(this.props.match.params.id);
    }
    
    render(){
        const notification = this.props.notification;
        return(
            <Fragment>
                {
                    this.props.loading ? 
                    (
                        <RctSectionLoader />
                    )
                    :
                    (
                        <PreviewNotification notification={notification} />
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
    getNotificationById
 })(PreviewNotificationContainer)