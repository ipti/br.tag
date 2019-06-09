/**
 * App Config File
 */
const AppConfig = {
    appLogo: require('Assets/img/site-logo.png'),          // App Logo
    brandName: 'TomCom',                                    // Brand Name
    navCollapsed: false,                                      // Sidebar collapse
    darkMode: false,                                          // Dark Mode
    boxLayout: false,                                         // Box Layout
    rtlLayout: false,                                         // RTL Layout
    miniSidebar: false,                                       // Mini Sidebar
    enableSidebarBackgroundImage: false,                      // Enable Sidebar Background Image
    sidebarImage: require('Assets/img/sidebar-4.jpg'),     // Select sidebar image
    isDarkSidenav: false,                                   // Set true to dark sidebar
    enableThemeOptions: false,                              // Enable Theme Options
    locale: {
        languageId: 'portugues',
        locale: 'pt',
        name: 'Português',
        icon: 'pt',
    },
    enableUserTour: process.env.NODE_ENV === 'production' ? true : false,  // Enable / Disable User Tour
    copyRightText: 'TAG © 2018 All Rights Reserved.',      // Copy Right Text
    // light theme colors
    themeColors: {
        'primary': '#5D92F4',
        'secondary': '#677080',
        'success': '#00D014',
        'danger': '#FF3739',
        'warning': '#FFB70F',
        'info': '#00D0BD',
        'dark': '#464D69',
        'default': '#FAFAFA',
        'greyLighten': '#A5A7B2',
        'grey': '#677080',
        'white': '#FFFFFF',
        'purple': '#896BD6',
        'yellow': '#D46B08'
    },
    // dark theme colors
    darkThemeColors: {
        darkBgColor: '#424242'
    },
    baseUrl : (window.location.host.indexOf(':') > 0) ? `http://${window.location.host.substr(0,window.location.host.indexOf(':'))}/` : `http://${window.location.host}/`,
    baseUrlApi : process.env.NODE_ENV === 'production' ? 'http://api.tag.ong.br':'http://api.tag.com/',

    citizen: {
        id: '5cfd7906aaf4d73096ccd566',
        access_token: "4ovQBzFHaw1h3j4mQgDYzwLOYcfnYhed_1537467423",
        institution: "5ceaa709de76c1afc98eecf3"
    }
}
export default AppConfig;
