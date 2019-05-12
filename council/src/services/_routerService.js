// routes
import Dashboard from 'Routes/dashboard';
import Complaint from 'Routes/complaint';
import Citizen from 'Routes/citizen';
import People from 'Routes/people';
import Notification from 'Routes/notification';
import Service from 'Routes/service';

export default [
	{
		path: 'dashboard',
		component: Dashboard
	},
	{
		path: 'complaint',
		component: Complaint
	},
	{
		path: 'citizen',
		component: Citizen
	},
	{
		path: 'citizen/view',
		component: Citizen
	},
	{
		path: 'people',
		component: People
	},
	{
		path: 'notification',
		component: Notification
	},
	{
		path: 'service',
		component: Service
	},
]