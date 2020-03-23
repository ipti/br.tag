/**
* Email App Sidebar
* Used To Filter Mail List
*/
import React, { Component } from 'react';
import List from '@material-ui/core/List';
import ListItem from '@material-ui/core/ListItem';
import { connect } from 'react-redux';
import { withRouter } from 'react-router-dom';
import classnames from 'classnames';
import { Scrollbars } from 'react-custom-scrollbars';

// helpers
import { getAppLayout } from 'Helpers/helpers';

// actions
import { filterEmails } from 'Actions';

//Intl Message
import IntlMessages from 'Util/IntlMessages';

class EmailAppSidebar extends Component {

	/**
	 * Navigate To Folder Emails
	 */
	navigateTo(key) {
		const { match, history } = this.props;
		history.push(`${match.url}/complaint/${key}`);
	}

	/**
	 * Filter Emails
	 */
	filterEmails(label) {
		this.props.filterEmails(label);
	}

	/**
	 * Get Scroll Height
	 */
	getScrollHeight() {
		const { location } = this.props;
		const appLayout = getAppLayout(location)
		switch (appLayout) {
			case 'app':
				return 'calc(100vh - 200px)';
			case 'agency':
				return 'calc(100vh - 400px)';
			case 'horizontal':
				return 'calc(100vh - 250px)';
			default:
				break;
		}
	}

	render() {
		const { folders, selectedFolder, labels } = this.props;
		return (
			<Scrollbars
				className="rct-scroll"
				autoHide
				style={{ height: this.getScrollHeight() }}
			>
				<div className="sidebar-filters-wrap">
					<div className="filters">
						<List className="py-0">
							{folders.map((folder, key) => (
								<ListItem
									button
									key={key}
									onClick={() => this.navigateTo(folder.handle)}
									className={classnames({ 'item-active': selectedFolder === folder.id })}>
									<i className={`mr-20 zmdi zmdi-${folder.icon}`} /><span className="filter-title"><IntlMessages id={folder.title} /></span>
								</ListItem>
							))}
						</List>
					</div>
					
				</div>
			</Scrollbars>
		);
	}
}

// map state to props
const mapStateToProps = ({ emailApp }) => {
	return emailApp;
};

export default withRouter(connect(mapStateToProps, {
	filterEmails
})(EmailAppSidebar));
