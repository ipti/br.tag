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

const AsyncComplaintListReceiveComponent = Loadable({
	loader: () => import("Routes/complaint/list"),
	loading: () => <RctPageLoader />,
});

const AsyncComplaintListAnalisysComponent = Loadable({
	loader: () => import("Routes/complaint/list"),
	loading: () => <RctPageLoader />,
});

const AsyncComplaintListForwardComponent = Loadable({
	loader: () => import("Routes/complaint/list"),
	loading: () => <RctPageLoader />,
});

const AsyncComplaintListCompletedComponent = Loadable({
	loader: () => import("Routes/complaint/list"),
	loading: () => <RctPageLoader />,
});

const AsyncComplaintInsertComponent = Loadable({
	loader: () => import("Routes/complaint/insert"),
	loading: () => <RctPageLoader />,
});

const AsyncComplaintFormalizeComponent = Loadable({
	loader: () => import("Routes/complaint/formalize"),
	loading: () => <RctPageLoader />,
});

const AsyncComplaintUpdateComponent = Loadable({
	loader: () => import("Routes/complaint/update"),
	loading: () => <RctPageLoader />,
});

const AsyncComplaintViewComponent = Loadable({
	loader: () => import("Routes/complaint/view"),
	loading: () => <RctPageLoader />,
});

/*---------------- Citizen ------------------*/

const AsyncCitizenComponent = Loadable({
	loader: () => import("Routes/citizen/index.js"),
	loading: () => <RctPageLoader />,
});

const AsyncCitizenFollowComponent = Loadable({
	loader: () => import("Routes/citizen/follow"),
	loading: () => <RctPageLoader />,
});

const AsyncCitizenViewerComponent = Loadable({
	loader: () => import("Routes/citizen/viewer"),
	loading: () => <RctPageLoader />,
});

const AsyncCitizenFormComponent = Loadable({
	loader: () => import("Routes/citizen/form"),
	loading: () => <RctPageLoader />,
});

/*---------------- People ------------------*/

const AsyncPeopleContainer = Loadable({
	loader: () => import("Container/People/PeopleContainer"),
	loading: () => <RctPageLoader />,
});

const AsyncPeopleFormContainer = Loadable({
	loader: () => import("Container/People/PeopleFormContainer"),
	loading: () => <RctPageLoader />,
});

/*---------------- Notification ------------------*/

const AsyncNotificationContainer = Loadable({
	loader: () => import("Container/Notification/NotificationContainer"),
	loading: () => <RctPageLoader />,
});

const AsyncNotificationFormContainer = Loadable({
	loader: () => import("Container/Notification/NotificationFormContainer"),
	loading: () => <RctPageLoader />,
});

const AsyncPreviewNotificationContainer = Loadable({
	loader: () => import("Container/Notification/PreviewNotificationContainer"),
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
	AsyncComplaintListReceiveComponent,
	AsyncComplaintListAnalisysComponent,
    AsyncComplaintListForwardComponent,
    AsyncComplaintListCompletedComponent,
	AsyncComplaintInsertComponent,
	AsyncComplaintFormalizeComponent,
	AsyncComplaintUpdateComponent,
	AsyncComplaintViewComponent,
	AsyncCitizenFollowComponent,
	AsyncCitizenViewerComponent,
	AsyncCitizenFormComponent,
	AsyncCitizenComponent,
	AsyncPeopleContainer,
	AsyncPeopleFormContainer,
	AsyncNotificationContainer,
	AsyncNotificationFormContainer,
	AsyncPreviewNotificationContainer,
};
