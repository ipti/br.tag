// routes
import Dashboard from 'Routes/dashboard';
import Complaint from 'Routes/complaint';
import Mail from 'Routes/mail';

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
		path: 'mail',
		component: Mail
	}
]