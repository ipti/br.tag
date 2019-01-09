// sidebar nav links
export default {
	category1: [
		{
			"menu_title": "sidebar.menu.complaint",
			"menu_icon": "zmdi zmdi-notifications-active",
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
	]
}
