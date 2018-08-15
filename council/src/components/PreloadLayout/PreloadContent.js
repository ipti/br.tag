/**
 * Preload Content
 */

import React, { Component, Fragment } from 'react'

//Pre Loader components
import PreloadWidget from './PreloadWidget';
import PreloadTitlebar from './PreloadTitlebar';

export default class PreloadContent extends Component {
	render() {
		return (
			<Fragment>
				<PreloadTitlebar />
				<div className="row">
					<div className="col-sm-6 col-md-4 col-lg-4 w-8-half-block">
						<PreloadWidget />
					</div>
					<div className="col-sm-6 col-md-4 col-lg-4 w-8-half-block">
						<PreloadWidget />
					</div>
					<div className="col-sm-6 col-md-4 col-lg-4 w-8-half-block">
						<PreloadWidget />
					</div>
					<div className="col-sm-6 col-md-4 col-lg-4 w-8-half-block">
						<PreloadWidget />
					</div>
					<div className="col-sm-6 col-md-4 col-lg-4 w-8-half-block">
						<PreloadWidget />
					</div>
					<div className="col-sm-6 col-md-4 col-lg-4 w-8-half-block">
						<PreloadWidget />
					</div>
					<div className="col-sm-6 col-md-4 col-lg-4 w-8-half-block">
						<PreloadWidget />
					</div>
					<div className="col-sm-6 col-md-4 col-lg-4 w-8-half-block">
						<PreloadWidget />
					</div>
					<div className="col-sm-6 col-md-4 col-lg-4 w-8-half-block">
						<PreloadWidget />
					</div>
				</div>
			</Fragment>
		)
	}
}
