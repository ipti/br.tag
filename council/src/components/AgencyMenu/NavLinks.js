// Agency nav links
export default {
   category1: [
      {
         "menu_title": "sidebar.dashboard",
         "menu_icon": "zmdi zmdi-view-dashboard",
         "child_routes": [
            {
               "path": "/app/dashboard/ecommerce",
               "menu_title": "sidebar.ecommerce",
               exact: true
            },
            {
               "path": "/horizontal/dashboard/saas",
               "menu_title": "sidebar.saas",
               exact: true
            },
            {
               "path": "/agency/dashboard/agency",
               "menu_title": "sidebar.agency",
               exact: true
            },
            {
               "path": "/boxed/dashboard/news",
               "menu_title": "sidebar.news",
               exact: true
            }
         ]
      }
   ]
}
