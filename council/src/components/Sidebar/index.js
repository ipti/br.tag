/**
 * Reactify Sidebar
 */
import React, { Component, Fragment } from 'react';
import classNames from 'classnames';
import { withRouter } from 'react-router-dom';
import { connect } from 'react-redux';
import { Link } from 'react-router-dom';
import { Scrollbars } from 'react-custom-scrollbars';
import $ from 'jquery';

// redux actions
import { collapsedSidebarAction } from 'Actions';

// components
import UserBlock from './UserBlock';
import SidebarContent from './SidebarContent';
import AgencySidebar from '../AgencyMenu/AgencySidebar';

class Sidebar extends Component {

	componentWillMount() {
		this.updateDimensions();
	}

	shouldComponentUpdate(nextProps) {
		const { enableSidebarBackgroundImage, selectedSidebarImage, isDarkSidenav } = this.props;
		if (enableSidebarBackgroundImage !== nextProps.enableSidebarBackgroundImage || selectedSidebarImage !== nextProps.selectedSidebarImage || isDarkSidenav !== nextProps.isDarkSidenav) {
			return true
		} else {
			return false
		}
	}

	componentDidMount() {
		window.addEventListener("resize", this.updateDimensions);
	}

	componentWillUnmount() {
		window.removeEventListener("resize", this.updateDimensions);
	}

	componentWillReceiveProps(nextProps) {
		const { windowWidth } = this.state;
		const { collapsedSidebar } = this.props;
		if (nextProps.location !== this.props.location) {
			if (windowWidth <= 1199) {
				this.props.collapsedSidebarAction(false);
			}
		}
	}

	updateDimensions = () => {
		this.setState({ windowWidth: $(window).width(), windowHeight: $(window).height() });
	}

	render() {
		const { enableSidebarBackgroundImage, selectedSidebarImage, isDarkSidenav, agencySidebar } = this.props;
		return (
			<Fragment>
				<div
					className={classNames('rct-sidebar', { 'background-none': !enableSidebarBackgroundImage })}
					style={{ backgroundImage: enableSidebarBackgroundImage ? `url(${selectedSidebarImage})` : 'none' }}
				>
					<div className={classNames("rct-sidebar-content", { "sidebar-overlay-dark": isDarkSidenav, 'sidebar-overlay-light': !isDarkSidenav })}>
						<div className="site-logo">
							<Link to="/" className="logo-mini">
								<img src={require('Assets/img/appLogo.png')} className="mr-15" alt="site logo" width="35" height="35" />
							</Link>
							<Link to="/" className="logo-normal">
								<img src={require('Assets/img/appLogoText.png')} className="img-fluid" alt="site-logo" width="67" height="17" />
							</Link>
						</div>
						<div className="rct-sidebar-wrap">
							<Scrollbars
								className="rct-scroll"
								autoHide
								autoHideDuration={100}
								style={{ height: 'calc(100vh - 60px)' }}
							>
								<UserBlock />
								{!agencySidebar ?
									<SidebarContent />
									:
									<AgencySidebar />
								}
							</Scrollbars>
						</div>
					</div>
				</div>
			</Fragment>
		);
	}
}

// map state to props
const mapStateToProps = ({ settings }) => {
	const { enableSidebarBackgroundImage, selectedSidebarImage, collapsedSidebar, isDarkSidenav } = settings;
	return { enableSidebarBackgroundImage, selectedSidebarImage, collapsedSidebar, isDarkSidenav };
};

export default withRouter(connect(mapStateToProps, {
	collapsedSidebarAction,
})(Sidebar));
