/**
 * Activity Stats Widget
 */
import React, { Fragment } from 'react';

const Activity = () => (
	<Fragment>
		<div className="p-20">
			<p className="lightermb-0">Today</p>
		</div>
		<div className="media-listing">
			<ul className="list-unstyled">
				<li className="media border-bottom">
					<img
						src={require('Assets/avatars/user-1.jpg')}
						className="rounded-circle mr-20 img-fluid"
						alt="user profile"
						width="60"
						height="60"
					/>
					<div className="media-body">
						<span className="fw-bold">Louise Kate <span className="text-muted fs-12">@louisekate</span></span>
						<p className="mb-0 text-muted">The new common language will be more simple and regular than the existing European languages. It will be as simple as Occidental; in fact, it will be Occidental.</p>
					</div>
				</li>
				<li className="media border-bottom">
					<img
						src={require('Assets/avatars/user-2.jpg')}
						className="rounded-circle mr-20 img-fluid"
						alt="user profile"
						width="60"
						height="60"
					/>
					<div className="media-body">
						<span className="fw-bold">Annie Lee <span className="text-muted fs-12">@Annielee</span></span>
						<p className="text-muted">Posted new photo</p>
						<div className="img-post">
							<img
								src={require('Assets/img/weather-1.png')}
								className="img-fluid border-rad-sm"
								alt="profile post"
							/>
						</div>
					</div>
				</li>
				<li className="media border-bottom">
					<img
						src={require('Assets/avatars/user-3.jpg')}
						className="rounded-circle mr-20 img-fluid"
						alt="user profile"
						width="60"
						height="60"
					/>
					<div className="media-body">
						<span className="fw-bold mb-30 d-block">Mark Anthony <span className="text-muted fs-12">@louisekate</span></span>
						<div className="card p-30 bg-primary">
							<h3 className="mb-0 text-white">The new common language will be more simple and regular than the existing European languages.</h3>
						</div>
					</div>
				</li>
				<li className="media border-bottom">
					<img
						src={require('Assets/avatars/user-4.jpg')}
						className="rounded-circle mr-20 img-fluid"
						alt="user profile"
						width="60"
						height="60"
					/>
					<div className="media-body">
						<span className="fw-bold">Annie Lee <span className="small">@louisekate</span></span>
						<p className="text-muted">Posted 4 photos</p>
						<ul className="list-unstyled row no-gutters">
							<li className="col-sm-6 col-md-3 mb-15 d-xxs-half-block">
								<img
									src={require('Assets/img/gallery-1.jpg')}
									className="img-fluid"
									width="200"
									height="200"
									alt="gallery post"
								/>
							</li>
							<li className="col-sm-6 col-md-3 mb-15 d-xxs-half-block">
								<img
									src={require('Assets/img/gallery-2.jpg')}
									className="img-fluid"
									width="200"
									height="200"
									alt="gallery post"
								/>
							</li>
							<li className="col-sm-6 col-md-3 mb-15 d-xxs-half-block">
								<img
									src={require('Assets/img/gallery-3.jpg')}
									className="img-fluid"
									width="200"
									height="200"
									alt="gallery post"
								/>
							</li>
							<li className="col-sm-6 col-md-3 mb-15 d-xxs-half-block">
								<img
									src={require('Assets/img/gallery-4.jpg')}
									className="img-fluid"
									width="200"
									height="200"
									alt="gallery post"
								/>
							</li>
						</ul>
					</div>
				</li>
				<li className="media border-bottom">
					<img
						src={require('Assets/avatars/user-5.jpg')}
						className="rounded-circle mr-20 img-fluid"
						alt="user profile"
						width="60"
						height="60"
					/>
					<div className="media-body">
						<span className="fw-bold">Mark Anthony <span className="small">@louisekate</span></span>
						<p className="text-muted">Postd a new blog in website</p>
						<div className="media media-full">
							<img
								src={require('Assets/img/gallery-6.jpg')}
								className="img-fluid mr-20"
								alt="post"
								width="300"
								height="180"
							/>
							<div className="media-body">
								<h5 className="fw-bold">How to setup your estore in 10 min.</h5>
								<p>Praesent libero. Sed cursus ante dapibus diam. Sed nisi. Nulla quis sem at nibh elementum imperdiet.
                                Duis sagittis ipsum. Praesent mauris. Fusce nec tellus sed augue semper </p>
								<p>Praesent mauris. Fusce nec tellus sed augue semper</p>
							</div>
						</div>
					</div>
				</li>
			</ul>
		</div>
	</Fragment>
);

export default Activity;
