/**
 * Email Listing Component
 */
import React, { Component } from 'react';
import classnames from 'classnames';
import { connect } from 'react-redux';
import { Scrollbars } from 'react-custom-scrollbars';
import { Redirect, Route, Switch, withRouter } from 'react-router-dom';

// helpers
import { getAppLayout } from 'Helpers/helpers';

// folders
import Inbox from './Inbox';
import SentEmails from './SentEmails';
import DraftsEmails from './DraftsEmails';
import SpamEmails from './SpamEmails';
import TrashEmails from './TrashEmails';

// components
import EmailListingHeader from './EmailListingHeader';

class Folders extends Component {

	/**
	* Function to return label name
	*/
	getLabelNames = (taskLabels) => {
		let elements = [];
		const { labels } = this.props;
		for (const emailLabel of taskLabels) {
			for (const label of labels) {
				if (label.value === emailLabel) {
					let ele = <span key={label.value}
						className={classnames('badge badge-pill', { 'badge-success': label.value === 1, 'badge-primary': label.value === 2, 'badge-info': label.value === 3, 'badge-danger': label.value === 4 })}
					>
						{label.name}
					</span>;
					elements.push(ele);
				}
			}
		}
		return elements;
	}

	/**
	 * on delete email
	 */
	deleteEmail() {
		this.props.onDeleteEmail();
	}

	/**
	 * Get Scroll Height
	 */
	getScrollHeight() {
		const { location } = this.props;
		const appLayout = getAppLayout(location)
		switch (appLayout) {
			case 'app':
				return 'calc(100vh - 130px)';
			case 'agency':
				return 'calc(100vh - 310px)';
			case 'horizontal':
				return 'calc(100vh - 177px)';
			default:
				break;
		}
	}

	render() {
		const { match, currentEmail } = this.props;
		return (
			<div className="all-mails-list">
				{currentEmail === null &&
					<EmailListingHeader />
				}
				<Scrollbars
					className="rct-scroll"
					autoHide
					style={{ height: this.getScrollHeight() }}
				>
					<React.Fragment>
						<Switch>
							<Redirect exact from={`${match.url}/`} to={`${match.url}/inbox`} />
							<Route path={`${match.url}/inbox`} component={Inbox} />
							<Route path={`${match.url}/sent`} component={SentEmails} />
							<Route path={`${match.url}/drafts`} component={DraftsEmails} />
							<Route path={`${match.url}/spam`} component={SpamEmails} />
							<Route path={`${match.url}/trash`} component={TrashEmails} />
						</Switch>
					</React.Fragment>
				</Scrollbars>
			</div>
		);
	}
}

// map state to props
const mapStateToProps = ({ emailApp }) => {
	return emailApp;
};

export default withRouter(connect(mapStateToProps)(Folders));
