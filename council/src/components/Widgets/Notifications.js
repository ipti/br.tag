/**
 * Notifications Widget
 */
import React, { Fragment, Component } from 'react';
import { withStyles } from '@material-ui/core/styles';
import SwipeableViews from 'react-swipeable-views';
import AppBar from '@material-ui/core/AppBar';
import Tabs from '@material-ui/core/Tabs';
import Tab from '@material-ui/core/Tab';
import { Scrollbars } from 'react-custom-scrollbars';
import Typography from '@material-ui/core/Typography';

// api
import api from 'Api';

// intl messages
import IntlMessages from 'Util/IntlMessages';

function TabContainer({ children, dir }) {
  return (
    <Typography component="div" dir={dir} style={{ padding: 8 * 3 }}>
      {children}
    </Typography>
  );
}

class Notifications extends Component {

  state = {
    value: 0,
    messages: null,
    notificationTypes: null,
    notifications: null
  };

  componentDidMount() {
    this.getMessages();
    this.getNotificationTypes();
    this.getNotifications();
  }

  // get messages
  getMessages() {
    api.get('messages.js')
      .then((response) => {
        this.setState({ messages: response.data });
      })
      .catch(error => {
        console.log(error);
      })
  }

  // get notification types
  getNotificationTypes() {
    api.get('notificationTypes.js')
      .then((response) => {
        this.setState({ notificationTypes: response.data });
      })
      .catch(error => {
        console.log(error);
      })
  }

  // get notifications
  getNotifications() {
    api.get('notifications.js')
      .then((response) => {
        this.setState({ notifications: response.data });
      })
      .catch(error => {
        console.log(error);
      })
  }

  handleChange = (event, value) => {
    this.setState({ value });
  };

  handleChangeIndex = index => {
    this.setState({ value: index });
  };

  /**
   * Function to return notification name
   */
  getNotificationName(notificationId) {
    const { notificationTypes } = this.state;
    if (notificationTypes) {
      for (const notificationType of notificationTypes) {
        if (notificationId === notificationType.id) {
          return (
            <span className={`text-${notificationType.class} mr-5`}>
              <i className={`zmdi zmdi-${notificationType.icon}`}></i> {notificationType.Name}
            </span>
          );
        }
      }
    }
  }

  render() {
    const { theme } = this.props;
    const { messages, notifications } = this.state;
    return (
      <Fragment>
        <AppBar position="static" color="default">
          <Tabs
            value={this.state.value}
            onChange={this.handleChange}
            indicatorColor="primary"
            textColor="primary"
            fullWidth
          >
            <Tab label={<IntlMessages id="widgets.recentNotifications" />} />
            <Tab label={<IntlMessages id="widgets.messages" />} />
          </Tabs>
        </AppBar>
        <Scrollbars className="rct-scroll" autoHeight autoHeightMin={100} autoHeightMax={375} autoHide>
          <SwipeableViews
            axis={theme.direction === 'rtl' ? 'x-reverse' : 'x'}
            index={this.state.value}
            onChangeIndex={this.handleChangeIndex}>
            <div className="card mb-0 notification-box">
              <TabContainer dir={theme.direction}>
                <ul className="list-inline mb-0">
                  {notifications && notifications.map((notification, key) => (
                    <li className="d-flex justify-content-between" key={key}>
                      <div className="align-items-start">
                        <p className="mb-5 message-head">
                          {this.getNotificationName(notification.notificationId)}
                          {notification.date}
                        </p>
                        <h5 className="mb-5">{notification.userName}</h5>
                        <p className="mb-0 text-muted">{notification.notification}</p>
                      </div>
                      <div className="align-items-end notify-user">
                        <img src={notification.userAvatar} alt="notify user" className="rounded-circle" width="50" height="50" />
                      </div>
                    </li>
                  ))}
                </ul>
              </TabContainer>
            </div>
            <div className="card mb-0 notification-box">
              <TabContainer dir={theme.direction}>
                <ul className="list-inline mb-0">
                  {messages && messages.map((message, key) => (
                    <li className="d-flex justify-content-between" key={key}>
                      <div className="align-items-start">
                        <p className="mb-5 message-head">
                          <span className="text-primary mr-5">
                            <i className="zmdi zmdi-comment-alt-text"></i> <IntlMessages id="widgets.messages" /></span> {message.date}
                        </p>
                        <h5 className="mb-5">{message.from.userName}</h5>
                        <p className="mb-0 text-muted">{message.message}</p>
                      </div>
                      <div className="align-items-end notify-user">
                        <img src={message.from.userAvatar} alt="notify user" className="rounded-circle" width="50" height="50" />
                      </div>
                    </li>
                  ))}
                </ul>
              </TabContainer>
            </div>
          </SwipeableViews>
        </Scrollbars>
      </Fragment>
    );
  }
}

export default withStyles(null, { withTheme: true })(Notifications);
