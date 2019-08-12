// sidebar nav links
export default {
	complaint: [
		{
			"menu_title": "sidebar.menu.complaint",
			"menu_icon": "zmdi zmdi-comment-alt-text",
			"child_routes": [
				{
					"menu_title": "sidebar.menu.complaint.list",
					"path": "/app/complaint/list",
				},
				{
					"path": "/app/complaint/insert",
					"menu_title": "sidebar.menu.complaint.insert"
				}
			]
		}
	],
	notification: [
		{
			"menu_title": "sidebar.menu.notification",
			"menu_icon": "zmdi zmdi-notifications-active",
			"child_routes": [
				{
					"menu_title": "sidebar.menu.notification.list",
					"path": "/app/notification/list",
				},
				{
					"path": "/app/notification/form",
					"menu_title": "sidebar.menu.notification.insert"
				}
			]
		}
	],
	food: [
		{
			"menu_title": "sidebar.menu.food",
			"menu_icon": "zmdi zmdi-cutlery",
			"child_routes": [
				{
					"menu_title": "sidebar.menu.food.list",
					"path": "/app/food/list",
				},
				{
					"path": "/app/food/form",
					"menu_title": "sidebar.menu.food.insert"
				}
			]
		}
	],
	fact: [
		{
			"menu_title": "sidebar.menu.fact",
			"menu_icon": "zmdi zmdi-assignment",
			"child_routes": [
				{
					"menu_title": "sidebar.menu.fact.list",
					"path": "/app/fact/list",
				},
				{
					"path": "/app/fact/form",
					"menu_title": "sidebar.menu.fact.insert"
				}
			]
		}
	],
	attendance: [
		{
			"menu_title": "sidebar.menu.attendance",
			"menu_icon": "zmdi zmdi-assignment",
			"child_routes": [
				{
					"menu_title": "sidebar.menu.attendance.list",
					"path": "/app/attendance/list",
				},
				{
					"path": "/app/attendance/form",
					"menu_title": "sidebar.menu.attendance.insert"
				}
			]
		}
	],
	housing: [
		{
			"menu_title": "sidebar.menu.housing",
			"menu_icon": "zmdi zmdi-home",
			"child_routes": [
				{
					"menu_title": "sidebar.menu.housing.list",
					"path": "/app/housing/list",
				},
				{
					"path": "/app/housing/form",
					"menu_title": "sidebar.menu.housing.insert"
				}
			]
		}
	],
	warning: [
		{
			"menu_title": "sidebar.menu.warning",
			"menu_icon": "zmdi zmdi-alert-triangle",
			"child_routes": [
				{
					"menu_title": "sidebar.menu.warning.list",
					"path": "/app/warning/list",
				},
				{
					"path": "/app/warning/form",
					"menu_title": "sidebar.menu.warning.insert"
				}
			]
		}
	],
	report: [
		{
			"menu_title": "sidebar.menu.report",
			"menu_icon": "zmdi zmdi-collection-text",
			"child_routes": [
				{
					"menu_title": "sidebar.menu.report.list",
					"path": "/app/report/list",
				},
				{
					"path": "/app/report/form",
					"menu_title": "sidebar.menu.report.insert"
				}
			]
		}
	],
	service: [
		{
			"menu_title": "sidebar.menu.service",
			"menu_icon": "zmdi zmdi-mall",
			"child_routes": [
				{
					"menu_title": "sidebar.menu.service.list",
					"path": "/app/service/list",
				},
				{
					"path": "/app/service/form",
					"menu_title": "sidebar.menu.service.insert"
				}
			]
		}
	],
	ficai: [
		{
			"menu_title": "sidebar.menu.ficai",
			"menu_icon": "zmdi zmdi-face",
			"child_routes": [
				{
					"menu_title": "sidebar.menu.ficai.list",
					"path": "/app/ficai/list",
				},
				{
					"path": "/app/ficai/form",
					"menu_title": "sidebar.menu.ficai.insert"
				}
			]
		}
	],
	people: [
		{
			"menu_title": "sidebar.menu.people",
			"menu_icon": "zmdi zmdi-account",
			"child_routes": [
				{
					"menu_title": "sidebar.menu.people.list",
					"path": "/app/people/list",
				},
				{
					"path": "/app/people/form",
					"menu_title": "sidebar.menu.people.insert"
				}
			]
		}
	],
}
