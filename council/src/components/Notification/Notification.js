import React, { Component } from 'react';
import Title from "Components/Title/Title";
import List from "Components/List/List";
import PropTypes from 'prop-types';


class Notification extends Component{

    render(){
        return (
            <div className="notification-content">
                <Title title="Notificações" />
                <List items={this.props.notifications} />
            </div>
        );
    }
}

Notification.propTypes = {
    notifications: PropTypes.array.isRequired,
};

export default Notification;