/**
 * Remiders Slider
 */
import React from 'react';

export default class Reminders extends React.Component {
	render() {
		return (
			<div className="lazy-up">
				<div className="card p-0">
					<div className="media p-20">
						<div className="mr-25">
							<img
								src={require('Assets/avatars/user-2.jpg')}
								className="rounded-circle"
								alt="user profile"
								width="90"
								height="90"
							/>
						</div>
						<div className="media-body pt-10">
							<span className="mb-5 text-pink fs-14 d-block">Reminder</span>
							<h4 className="mb-5">Call to Sana</h4>
							<span className="text-muted fs-14"><i className="ti-time"></i> 05:00 AM, 8 Jun 2017</span>
						</div>
					</div>
					<div className="card-footer">
						<div className="d-flex justify-content-between">
							<div className="d-flex align-items-start">
								<a className="fs-12 text-muted" href="javascript:void(0);"><i className="ti-mobile mr-5"></i>+01 123 456 7890</a>
							</div>
						</div>
					</div>
				</div>
			</div>
		);
	}
}
