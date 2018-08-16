/**
 * Sidebar Content
 */
import React, { Component } from 'react';
import List from '@material-ui/core/List';
import ListSubheader from '@material-ui/core/ListSubheader';
import { withRouter } from 'react-router-dom';
import { connect } from 'react-redux';
import Button from '@material-ui/core/Button';
import EmailAppSidebar from 'Routes/mail/components/EmailAppSidebar';
import { Redirect, Route, Switch, NavLink } from 'react-router-dom';

import IntlMessages from 'Util/IntlMessages';

import NavMenuItem from './NavMenuItem';

// redux actions
import { onToggleMenu } from 'Actions';

class SidebarContent extends Component {

    toggleMenu(menu, stateCategory) {
        let data = {
            menu,
            stateCategory
        }
        this.props.onToggleMenu(data);
    }

    render() {
        const { sidebarMenus } = this.props.sidebar;
        const { classes, theme, match, sendingEmail } = this.props;
        return (
            <div className="rct-mail-wrapper">
            <div className="mail-sidebar-wrap">
				<div className="user-mail d-flex justify-content-between p-10">
					<div className="media align-items-center">
						<img
							src={require('Assets/avatars/user-15.jpg')}
							alt="user prof"
							className="img-fluid rounded-circle mr-10"
							width="40"
							height="40"
						/>
						<div className="media-body mt-1">
							<h4 className="mb-0">Carlos Alberto</h4>
							<p className="text-muted mb-0">calberto@conselho</p>
						</div>
					</div>
				</div>
                <EmailAppSidebar />
			    </div>
					
            </div>
      
        );
    }
}

// map state to props
const mapStateToProps = ({ sidebar }) => {
    return { sidebar };
};

export default withRouter(connect(mapStateToProps, {
    onToggleMenu
})(SidebarContent));
