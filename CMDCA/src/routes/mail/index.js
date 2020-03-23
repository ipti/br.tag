/**
 * Mail App
 */
import React from 'react';
import { withStyles } from '@material-ui/core/styles';
import Drawer from '@material-ui/core/Drawer';
import AppBar from '@material-ui/core/AppBar';
import Toolbar from '@material-ui/core/Toolbar';
import Typography from '@material-ui/core/Typography';
import IconButton from '@material-ui/core/IconButton';
import Button from '@material-ui/core/Button';
import Hidden from '@material-ui/core/Hidden';
import MenuIcon from '@material-ui/icons/Menu';
import { connect } from 'react-redux';
import { Card, CardBody } from 'reactstrap';
import { Redirect, Route, Switch, NavLink } from 'react-router-dom';
import IntlMessages from 'Util/IntlMessages';

// actions
import { getEmails } from 'Actions';

// components
import Folders from './components/Folders';
import EmailAppSidebar from './components/EmailAppSidebar';
import ComposeEmail from './components/ComposeEmail';
import EmailSearch from './components/EmailSearch';

const drawerWidth = 280;

const styles = theme => ({
	root: {
		flexGrow: 1,
		height: 'auto',
		zIndex: 1,
		overflow: 'hidden',
		position: 'relative',
		display: 'flex',
		width: '100%',
	},
	appBar: {
		position: 'absolute',
		marginLeft: theme.direction !== 'rtl' ? drawerWidth : 0,
		marginRight: theme.direction === 'rtl' ? drawerWidth : 0,
		[theme.breakpoints.up('md')]: {
			width: `calc(100% - ${drawerWidth}px)`,
		}
	},
	navIconHide: {
		[theme.breakpoints.up('md')]: {
			display: 'none',
		},
	},
	toolbar: theme.mixins.toolbar,
	drawerPaper: {
		width: drawerWidth,
		[theme.breakpoints.up('md')]: {
			position: 'relative',
		},
	},
	content: {
		flexGrow: 1,
		backgroundColor: theme.palette.background.default,
		padding: theme.spacing.unit * 3,
	},
});

class MailApp extends React.Component {

	state = {
		mobileOpen: false,
	};

	handleDrawerToggle = () => {
		this.setState({ mobileOpen: !this.state.mobileOpen });
	};

	componentDidMount() {
		this.props.getEmails();
	}

	render() {
		const { classes, theme, match, sendingEmail } = this.props;
		const drawer = (
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
							<h4 className="mb-0">Braxton Hudson</h4>
							<p className="text-muted mb-0">braxton@example.com</p>
						</div>
					</div>
				</div>
				<div className="p-20">
					<Button
						component={NavLink}
						to={`${match.url}/compose`}
						raised="true"
						size="large"
						className="btn-danger text-white btn-block btn-lg">
						<i className="zmdi zmdi-border-color mr-20"></i>
						<IntlMessages id="widgets.composeMail" />
					</Button>
				</div>
				<EmailAppSidebar />
			</div>
		);
		return (
			<div className="rct-mail-wrapper">
				<div className={classes.root}>
					<AppBar className={classes.appBar}>
						<Toolbar className="d-flex justify-content-between">
							<IconButton
								color="inherit"
								aria-label="open drawer"
								onClick={this.handleDrawerToggle}
								className={classes.navIconHide}>
								<MenuIcon />
							</IconButton>
							<Typography variant="title" color="inherit" noWrap>
								<IntlMessages id="sidebar.inbox" />
							</Typography>
							<EmailSearch />
						</Toolbar>
					</AppBar>
					<Hidden mdUp>
						<Drawer
							variant="temporary"
							anchor={theme.direction === 'rtl' ? 'right' : 'left'}
							open={this.state.mobileOpen}
							onClose={this.handleDrawerToggle}
							classes={{
								paper: classes.drawerPaper,
							}}
							ModalProps={{
								keepMounted: true,
							}}
						>
							{drawer}
						</Drawer>
					</Hidden>
					<Hidden smDown implementation="css">
						<Drawer
							variant="permanent"
							open
							classes={{
								paper: classes.drawerPaper,
							}}
						>
							{drawer}
						</Drawer>
					</Hidden>
					<div className={classes.content}>
						<div className={classes.toolbar} />
						{sendingEmail &&
							<div className="sending-email-loader d-flex justify-content-center">
								<Card>
									<CardBody>
										<h5>Sending.....</h5>
									</CardBody>
								</Card>
							</div>
						}
						<Switch>
							<Redirect exact from={`${match.url}/`} to={`${match.url}/folder`} />
							<Route path={`${match.url}/folder`} component={Folders} />
							<Route path={`${match.url}/compose`} component={ComposeEmail} />
						</Switch>
					</div>
				</div>
			</div>
		);
	}
}

// map state to props
const mapStateToProps = ({ emailApp }) => {
	const { currentEmail, sendingEmail } = emailApp;
	return { currentEmail, sendingEmail };
}

export default connect(mapStateToProps, {
	getEmails
})(withStyles(styles, { withTheme: true })(MailApp));
