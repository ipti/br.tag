import React, { Component } from 'react';
import PreviewRoute from 'Routes/preview';
import { Route, withRouter } from 'react-router-dom';
import { connect } from 'react-redux';

class DocumentLayout extends Component {
	render() {
		const { match } = this.props;
		return (
			<Route path={`${match.url}/preview`} component={PreviewRoute} />
		);
	}
}

export default withRouter(connect(null)(DocumentLayout));
