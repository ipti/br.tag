// routes
import Dashboard from 'Routes/dashboard';
import Complaint from 'Routes/complaint';
import Citizen from 'Routes/citizen';
import People from 'Routes/people';
import Notification from 'Routes/notification';
import Food from 'Routes/food';
import Warning from 'Routes/warning';
import Service from 'Routes/service';
import Housing from 'Routes/housing';
import Fact from 'Routes/fact';

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
        path: 'food',
        component: Food
    },
    {
        path: 'warning',
        component: Warning
    },
	{
		path: 'service',
		component: Service
	},
	{
		path: 'housing',
		component: Housing
	},
	{
		path: 'fact',
		component: Fact
	}
]