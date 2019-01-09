/**
 * Sidebar Content
 */
import React, { Component } from 'react';
import { withRouter } from 'react-router-dom';
import { connect } from 'react-redux';
import EmailAppSidebar from 'Routes/mail/components/EmailAppSidebar';

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
        const username = sessionStorage.getItem('user_name');
        const email = sessionStorage.getItem('user_email');
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
							<h4 className="mb-0">{username}</h4>
							<p className="text-muted mb-0">{email}</p>
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
