import React, { Component } from 'react';
import { Helmet } from "react-helmet";
// page title bar
import PageTitleBar from 'Components/PageTitleBar/PageTitleBar';

// intl messages
import IntlMessages from 'Util/IntlMessages';

export default class Resolution extends Component {
	render() {
		return (
			<div className="blank-wrapper">
				<Helmet>
					<title>name page</title>
					<meta name="description" content="Reactify Blank Page" />
				</Helmet>
				<PageTitleBar title={<IntlMessages id="sidebar.resolution.list" />} match={this.props.match} />
			</div>
		);
	}
}