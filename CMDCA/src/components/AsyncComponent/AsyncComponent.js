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

/*---------------- Service ------------------*/

const AsyncServiceContainer = Loadable({
	loader: () => import("Container/Service/ServiceContainer"),
	loading: () => <RctPageLoader />,
});

const AsyncServiceFormContainer = Loadable({
	loader: () => import("Container/Service/ServiceFormContainer"),
	loading: () => <RctPageLoader />,
});

const AsyncPreviewServiceContainer = Loadable({
	loader: () => import("Container/Service/PreviewServiceContainer"),
	loading: () => <RctPageLoader />,
});

/*---------------- Report ------------------*/

const AsyncReportContainer = Loadable({
	loader: () => import("Container/Report/ReportContainer"),
	loading: () => <RctPageLoader />,
});

const AsyncReportFormContainer = Loadable({
	loader: () => import("Container/Report/ReportFormContainer"),
	loading: () => <RctPageLoader />,
});

const AsyncPreviewReportContainer = Loadable({
	loader: () => import("Container/Report/PreviewReportContainer"),
	loading: () => <RctPageLoader />,
});

/*---------------- Food ------------------*/

const AsyncFoodContainer = Loadable({
    loader: () => import("Container/Food/FoodContainer"),
    loading: () => <RctPageLoader />,
});

const AsyncFoodFormContainer = Loadable({
    loader: () => import("Container/Food/FoodFormContainer"),
    loading: () => <RctPageLoader />,
});

const AsyncPreviewFoodContainer = Loadable({
    loader: () => import("Container/Food/PreviewFoodContainer"),
    loading: () => <RctPageLoader />,
});

/*---------------- Warning ------------------*/

const AsyncWarningContainer = Loadable({
    loader: () => import("Container/Warning/WarningContainer"),
    loading: () => <RctPageLoader />,
});

const AsyncWarningFormContainer = Loadable({
    loader: () => import("Container/Warning/WarningFormContainer"),
    loading: () => <RctPageLoader />,
});

const AsyncPreviewWarningContainer = Loadable({
    loader: () => import("Container/Warning/PreviewWarningContainer"),
    loading: () => <RctPageLoader />,
});

/*---------------- Housing ------------------*/

const AsyncHousingContainer = Loadable({
	loader: () => import("Container/Housing/HousingContainer"),
	loading: () => <RctPageLoader />,
});

const AsyncHousingFormContainer = Loadable({
	loader: () => import("Container/Housing/HousingFormContainer"),
	loading: () => <RctPageLoader />,
});

const AsyncPreviewHousingContainer = Loadable({
    loader: () => import("Container/Housing/PreviewHousingContainer"),
    loading: () => <RctPageLoader />,
});

/*---------------- Fact ------------------*/

const AsyncFactContainer = Loadable({
	loader: () => import("Container/Fact/FactContainer"),
	loading: () => <RctPageLoader />,
});

const AsyncFactFormContainer = Loadable({
	loader: () => import("Container/Fact/FactFormContainer"),
	loading: () => <RctPageLoader />,
});

const AsyncPreviewFactContainer = Loadable({
    loader: () => import("Container/Fact/PreviewFactContainer"),
    loading: () => <RctPageLoader />,
});

/*---------------- Resolution ------------------*/

const AsyncResolutionComponent = Loadable({
	loader: () => import("Routes/resolution/index.js"),
	loading: () => <RctPageLoader />,
});

const AsyncResolutionFormContainer = Loadable({
	loader: () => import("Container/Resolution/ResolutionFormContainer.js"),
	loading: () => <RctPageLoader />,
});

const AsyncResolutionContainer = Loadable({
	loader: () => import("Container/Resolution/ResolutionContainer.js"),
	loading: () => <RctPageLoader />,
});

/*---------------- Home ------------------*/
const AsyncHomeContainer = Loadable({
	loader: () => import("Container/Home/HomeContainer.js"),
	loading: () => <RctPageLoader />,
});

/*---------------- Advisor ------------------*/
const AsyncAdvisorFormContainer = Loadable({
	loader: () => import("Container/Advisor/AdvisorFormContainer.js"),
	loading: () => <RctPageLoader />,
});

const AsyncAdvisorContainer = Loadable({
	loader: () => import("Container/Advisor/AdvisorContainer.js"),
	loading: () => <RctPageLoader />,
});

/*---------------- Schedule ------------------*/
const AsyncScheduleFormContainer = Loadable({
	loader: () => import("Container/Schedule/ScheduleFormContainer.js"),
	loading: () => <RctPageLoader />,
});

const AsyncScheduleFormEditContainer = Loadable({
	loader: () => import("Container/Schedule/ScheduleFormEditContainer.js"),
	loading: () => <RctPageLoader />,
});

const AsyncScheduleContainer = Loadable({
	loader: () => import("Container/Schedule/ScheduleContainer.js"),
	loading: () => <RctPageLoader />,
});

/*---------------- News ------------------*/
const AsyncNewsFormContainer = Loadable({
	loader: () => import("Container/News/NewsFormContainer.js"),
	loading: () => <RctPageLoader />,
});

const AsyncNewsContainer = Loadable({
	loader: () => import("Container/News/NewsContainer.js"),
	loading: () => <RctPageLoader />,
});

/*---------------- Finances ------------------*/
const AsyncFinancesFormContainer = Loadable({
	loader: () => import("Container/Finances/FinancesFormContainer.js"),
	loading: () => <RctPageLoader />,
});

const AsyncFinancesFormEditContainer = Loadable({
	loader: () => import("Container/Finances/FinancesFormEditContainer.js"),
	loading: () => <RctPageLoader />,
});

const AsyncFinancesContainer = Loadable({
	loader: () => import("Container/Finances/FinancesContainer.js"),
	loading: () => <RctPageLoader />,
});


/*---------------- Record ------------------*/
const AsyncRecordFormContainer = Loadable({
	loader: () => import("Container/Record/RecordFormContainer.js"),
	loading: () => <RctPageLoader />,
});

const AsyncRecordFormEditContainer = Loadable({
	loader: () => import("Container/Record/RecordFormEditContainer.js"),
	loading: () => <RctPageLoader />,
});

const AsyncRecordContainer = Loadable({
	loader: () => import("Container/Record/RecordContainer.js"),
	loading: () => <RctPageLoader />,
});

/*---------------- Notice ------------------*/
const AsyncNoticeFormContainer = Loadable({
	loader: () => import("Container/Notice/NoticeFormContainer.js"),
	loading: () => <RctPageLoader />,
});

const AsyncNoticeFormEditContainer = Loadable({
	loader: () => import("Container/Notice/NoticeFormEditContainer.js"),
	loading: () => <RctPageLoader />,
});

const AsyncNoticeContainer = Loadable({
	loader: () => import("Container/Notice/NoticeContainer.js"),
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
    AsyncFoodContainer,
    AsyncFoodFormContainer,
    AsyncWarningContainer,
    AsyncWarningFormContainer,
    AsyncPreviewWarningContainer,
    AsyncPreviewFoodContainer,
	AsyncServiceContainer,
	AsyncServiceFormContainer,
	AsyncPreviewServiceContainer,
	AsyncReportContainer,
	AsyncReportFormContainer,
	AsyncPreviewReportContainer,
	AsyncPreviewHousingContainer,
	AsyncHousingContainer,
	AsyncHousingFormContainer,
	AsyncPreviewFactContainer,
	AsyncFactContainer,
	AsyncFactFormContainer,
	AsyncResolutionComponent,
	AsyncResolutionFormContainer,
	AsyncResolutionContainer,
	AsyncHomeContainer,
	AsyncAdvisorFormContainer,
	AsyncAdvisorContainer,
	AsyncScheduleFormContainer,
	AsyncScheduleContainer,
	AsyncNewsFormContainer,
	AsyncNewsContainer,
	AsyncFinancesFormContainer,
	AsyncFinancesContainer,
	AsyncFinancesFormEditContainer,
	AsyncRecordFormContainer,
	AsyncRecordContainer,
	AsyncRecordFormEditContainer,
	AsyncNoticeFormContainer,
	AsyncNoticeContainer,
	AsyncNoticeFormEditContainer,
	AsyncScheduleFormEditContainer,
};
