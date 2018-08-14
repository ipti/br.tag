/**
 * Notification Component
 */
import React, { Component } from 'react';
import { Scrollbars } from 'react-custom-scrollbars';
import { UncontrolledDropdown, DropdownToggle, DropdownMenu } from 'reactstrap';
import Button from '@material-ui/core/Button';
import { Badge } from 'reactstrap';
import IconButton from '@material-ui/core/IconButton';
import Tooltip from '@material-ui/core/Tooltip';

// api
import api from 'Api';

// intl messages
import IntlMessages from 'Util/IntlMessages';

class Notifications extends Component {

  state = {
    notifications: null
  }

  componentDidMount() {
    this.getNotifications();
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

  render() {
    const { notifications } = this.state;
    return (
      <UncontrolledDropdown nav className="list-inline-item notification-dropdown">
        <DropdownToggle nav className="p-0">
          <Tooltip title="Notifications" placement="bottom">
            <IconButton className="shake" aria-label="bell">
              <i className="zmdi zmdi-notifications-active"></i>
              <Badge color="danger" className="badge-xs badge-top-right rct-notify">2</Badge>
            </IconButton>
          </Tooltip>
        </DropdownToggle>
        <DropdownMenu right>
		  		<div className="dropdown-content">
					<div className="dropdown-top d-flex justify-content-between rounded-top bg-primary">
						<span className="text-white font-weight-bold">
							<IntlMessages id="widgets.recentNotifications" />
						</span>
						<Badge color="warning">1 NEW</Badge>
					</div>
          		<Scrollbars className="rct-scroll" autoHeight autoHeightMin={100} autoHeightMax={280}>
						<ul className="list-unstyled dropdown-list">
						{notifications && notifications.map((notification, key) => (
							<li key={key}>
								<div className="media">
								<div className="mr-10">
									<img src={notification.userAvatar} alt="user profile" className="media-object rounded-circle" width="50" height="50" />
								</div>
								<div className="media-body pt-5">
									<div className="d-flex justify-content-between">
										<h5 className="mb-5 text-primary">{notification.userName}</h5>
										<span className="text-muted fs-12">{notification.date}</span>
									</div>
									<span className="text-muted fs-12 d-block">{notification.notification}</span>
									<Button className="btn-xs mr-10">
										<i className="zmdi zmdi-mail-reply mr-2"></i> <IntlMessages id="button.reply" />
									</Button>
									<Button className="btn-xs">
										<i className="zmdi zmdi-thumb-up mr-2"></i> <IntlMessages id="button.like" />
									</Button>
								</div>
								</div>
							</li>
						))}
						</ul>
					</Scrollbars>
				</div>
          	<div className="dropdown-foot p-2 bg-white rounded-bottom">
					<Button
						variant="raised"
						color="primary"
						className="mr-10 btn-xs bg-primary"
					>
						<IntlMessages id="button.viewAll" />
					</Button>
				</div>
        </DropdownMenu>
      </UncontrolledDropdown>
    );
  }
}

export default Notifications;
