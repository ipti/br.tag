/**
 * App.js Layout Start Here
 */
import React, { Component } from 'react';
import { connect } from 'react-redux';
import { Redirect, Route } from 'react-router-dom';
import { NotificationContainer } from 'react-notifications';

// rct theme provider
import RctThemeProvider from './RctThemeProvider';

//Horizontal Layout
import HorizontalLayout from './HorizontalLayout';

//Agency Layout
import AgencyLayout from './AgencyLayout';

//Main App
import RctDefaultLayout from './DefaultLayout';

// boxed layout
import RctBoxedLayout from './RctBoxedLayout';

// preview layout
import DocumentLayout from './DocumentLayout';


// async components
import {
	AsyncSessionLoginComponent,
	AsyncCitizenComponent
} from 'Components/AsyncComponent/AsyncComponent';


/**
 * Initial Path To Check Whether User Is Logged In Or Not
 */
const InitialPath = ({ component: Component, ...rest, authUser }) =>
	<Route
		{...rest}
		render={props =>
			authUser
				? <Component {...props} />
				: <Redirect
					to={{
						pathname: '/session/login',
						state: { from: props.location }
					}}
				/>}
	/>;

class App extends Component {
	render() {
		const { location, match, user } = this.props;
		if (location.pathname === '/') {
				return <Redirect to={'/app/home/index'} />;
		}
		return (
			<RctThemeProvider>
				<NotificationContainer />
				<InitialPath
					path={`${match.url}app`}
					authUser={localStorage.getItem('user')}
					component={RctDefaultLayout}
				/>
				<InitialPath
					path={`${match.url}document`}
					authUser={localStorage.getItem('user')}
					component={DocumentLayout}
				/>
				<Route path="/horizontal" component={HorizontalLayout} />
				<Route path="/agency" component={AgencyLayout} />
				<Route path="/boxed" component={RctBoxedLayout} />
				<Route path="/session/login" component={AsyncSessionLoginComponent} />
				<Route path="/session/logout" component={AsyncSessionLoginComponent} />
				<Route path="/citizen" component={AsyncCitizenComponent} />
			</RctThemeProvider>
		);
	}
}

// map state to props
const mapStateToProps = ({ authUser }) => {
	const user = localStorage.getItem('user');
	return user;
};

export default connect(mapStateToProps)(App);
