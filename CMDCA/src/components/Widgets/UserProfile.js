/**
 * User Profile
 */
import React, { Component } from 'react';
import Avatar from '@material-ui/core/Avatar';
import Button from '@material-ui/core/Button';

// intl messages
import IntlMessages from 'Util/IntlMessages';

export default class UserProfile extends Component {
	render() {
		return (
			<div className="user-profile-widget">
				<div className="bg-primary py-70"></div>
				<div className="p-20">
					<div className="d-flex user-avatar">
						<Avatar
							alt="user 2"
							src={require('Assets/avatars/user-2.jpg')}
							className="size-100 rounded-circle mr-15"
						/>
						<div className="user-info text-white pt-20">
							<h4 className="mb-0">Phoebe Henderson</h4>
							<span>CEO</span>
						</div>
					</div>
					<ul className="list-unstyled my-25">
						<li className="border-bottom py-10 d-flex align-items-center">
							<i className="zmdi zmdi-email mr-10 fs-14"></i>
							<a href="mail-to:phoebe@gmail.com" className="fs-14 text-dark">phoebe@gmail.com</a>
						</li>
						<li className="border-bottom py-10 d-flex align-items-center">
							<i className="zmdi zmdi-phone mr-10 fs-14"></i>
							<a href="tel:011234567890" className="fs-14 text-dark">+01 123 456 7890</a>
						</li>
						<li className="border-bottom py-10 fs-14 d-flex align-items-center">
							<i className="zmdi zmdi-account-box mr-10 fs-14"></i>
							e-51, Industrial area, Phase2, Mohali
                  </li>
					</ul>
					<div className="d-flex">
						<Button
							variant="raised"
							color="primary"
							className="text-white mr-10 mb-10 btn-xs"
						>
							<IntlMessages id="button.viewProfile" />
						</Button>
						<Button
							variant="raised"
							color="secondary"
							className="text-white btn-xs mb-10"
						>
							<IntlMessages id="button.sendMessage" />
						</Button>
					</div>
				</div>
			</div>
		)
	}
};

