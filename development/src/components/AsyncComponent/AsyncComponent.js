/**
 * AsyncComponent
 * Code Splitting Component / Server Side Rendering
 */
import React from 'react';
import Loadable from 'react-loadable';

// rct page loader
import RctPageLoader from 'Components/RctPageLoader/RctPageLoader';

// ecommerce dashboard
const AsyncEcommerceDashboardComponent = Loadable({
	loader: () => import("Routes/dashboard/ecommerce"),
	loading: () => <RctPageLoader />,
});

// agency dashboard
const AsyncSaasDashboardComponent = Loadable({
	loader: () => import("Routes/dashboard/saas"),
	loading: () => <RctPageLoader />,
});

// agency dashboard
const AsyncAgencyDashboardComponent = Loadable({
	loader: () => import("Routes/dashboard/agency"),
	loading: () => <RctPageLoader />,
});

// boxed dashboard
const AsyncNewsDashboardComponent = Loadable({
	loader: () => import("Routes/dashboard/news"),
	loading: () => <RctPageLoader />,
});

/*---------------- Session ------------------*/

// Session Login
const AsyncSessionLoginComponent = Loadable({
	loader: () => import("Routes/session/login"),
	loading: () => <RctPageLoader />,
});

/*---------------- Home Page ------------------*/
const AsyncHomeComponent = Loadable({
	loader: () => import("Routes/home"),
	loading: () => <RctPageLoader />,
});

/*---------------- Complaint ------------------*/

const AsyncComplaintListComponent = Loadable({
	loader: () => import("Routes/complaint/list"),
	loading: () => <RctPageLoader />,
});

const AsyncComplaintInsertComponent = Loadable({
	loader: () => import("Routes/complaint/insert"),
	loading: () => <RctPageLoader />,
});

const AsyncComplaintViewComponent = Loadable({
	loader: () => import("Routes/complaint/view"),
	loading: () => <RctPageLoader />,
});

export {
	AsyncEcommerceDashboardComponent,
	AsyncSaasDashboardComponent,
	AsyncAgencyDashboardComponent,
	AsyncNewsDashboardComponent,
	AsyncSessionLoginComponent,
	AsyncHomeComponent,
	AsyncComplaintListComponent,
	AsyncComplaintInsertComponent,
	AsyncComplaintViewComponent
};
