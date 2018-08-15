/**
 * App Settings Reducers
 */
import update from 'react-addons-update';

import {
	COLLAPSED_SIDEBAR,
	DARK_MODE,
	BOXED_LAYOUT,
	RTL_LAYOUT,
	MINI_SIDEBAR,
	SEARCH_FORM_ENABLE,
	CHANGE_THEME_COLOR,
	TOGGLE_SIDEBAR_IMAGE,
	SET_SIDEBAR_IMAGE,
	SET_LANGUAGE,
	START_USER_TOUR,
	STOP_USER_TOUR,
	TOGGLE_DARK_SIDENAV,
	CHANGE_AGENCY_LAYOUT_BG
} from 'Actions/types';

// app config
import AppConfig from 'Constants/AppConfig';

/**
 * initial app settings
 */
const INIT_STATE = {
	navCollapsed: AppConfig.navCollapsed,
	darkMode: AppConfig.darkMode,
	boxLayout: AppConfig.boxLayout,
	rtlLayout: AppConfig.rtlLayout,
	miniSidebar: AppConfig.miniSidebar,
	searchFormOpen: false, // search form by default false
	startUserTour: false,
	isDarkSidenav: AppConfig.isDarkSidenav,
	themes: [
		{
			id: 1,
			name: 'primary'
		},
		{
			id: 2,
			name: 'secondary'
		},
		{
			id: 3,
			name: 'warning'
		},
		{
			id: 4,
			name: 'info'
		},
		{
			id: 5,
			name: 'danger'
		},
		{
			id: 6,
			name: 'success'
		}
	],
	activeTheme: {
		id: 1,
		name: 'primary'
	},
	// sidebar background image
	sidebarBackgroundImages: [
		require('Assets/img/sidebar-1.jpg'),
		require('Assets/img/sidebar-2.jpg'),
		require('Assets/img/sidebar-3.jpg'),
		require('Assets/img/sidebar-4.jpg'),
	],
	enableSidebarBackgroundImage: AppConfig.enableSidebarBackgroundImage, // default enable sidebar background
	selectedSidebarImage: AppConfig.sidebarImage, // default sidebar background image
	locale: AppConfig.locale,
	languages: [
		{
			languageId: 'english',
			locale: 'en',
			name: 'English',
			icon: 'en',
		},
		{
			languageId: 'chinese',
			locale: 'zh',
			name: 'Chinese',
			icon: 'zh',
		},
		{
			languageId: 'russian',
			locale: 'ru',
			name: 'Russian',
			icon: 'ru',
		},
		{
			languageId: 'hebrew',
			locale: 'he',
			name: 'Hebrew',
			icon: 'he',
		},
		{
			languageId: 'french',
			locale: 'fr',
			name: 'French',
			icon: 'fr',
		},
		{
			languageId: 'saudi-arabia',
			locale: 'ar',
			name: 'Arabic',
			icon: 'ar',
		},
		{
			languageId: 'german',
			locale: 'de',
			name: 'German',
			icon: 'de',
		},
		{
			languageId: 'spanish',
			locale: 'es',
			name: 'Spanish',
			icon: 'es',
		},
		{
			languageId: 'japanese',
			locale: 'ja',
			name: 'Japanese',
			icon: 'ja',
		},
		{
			languageId: 'korean',
			locale: 'ko',
			name: 'Korean',
			icon: 'ko',
		},
		{
			languageId: 'italian',
			locale: 'it',
			name: 'Italian',
			icon: 'it',
		},
		{
			languageId: 'hungarian',
			locale: 'hu',
			name: 'Hungarian',
			icon: 'hu',
		},
		{
			languageId: 'portugues',
			locale: 'pt',
			name: 'Português',
			icon: 'pt',
		}
	],
	agencyLayoutBgColors: [
		{
			id: 1,
			class: 'bg-grdnt-violet',
			active: true
		},
		{
			id: 2,
			class: 'bg-grdnt-youtube'
		},
		{
			id: 3,
			class: 'bg-grdnt-primary-danger',
		},
		{
			id: 4,
			class: 'bg-grdnt-purple-danger'
		},
		{
			id: 5,
			class: 'bg-grdnt-purple-dark'
		}
	]
};

export default (state = INIT_STATE, action) => {
	switch (action.type) {

		// collapse sidebar
		case COLLAPSED_SIDEBAR:
			return { ...state, navCollapsed: action.isCollapsed };

		// start user tour
		case START_USER_TOUR:
			return { ...state, startUserTour: true };

		// stop user tour
		case STOP_USER_TOUR:
			return { ...state, startUserTour: false };

		// change theme color
		case CHANGE_THEME_COLOR:
			return { ...state, activeTheme: action.payload };

		// dark mode
		case DARK_MODE:
			return { ...state, darkMode: action.payload };

		// boxed layout
		case BOXED_LAYOUT:
			return { ...state, boxLayout: action.payload };

		// rtl layout
		case RTL_LAYOUT:
			return { ...state, rtlLayout: action.payload };

		// mini sidebar
		case MINI_SIDEBAR:
			return { ...state, miniSidebar: action.payload };

		// search form
		case SEARCH_FORM_ENABLE:
			document.body.classList.toggle('search-active', !state.searchFormOpen);
			return { ...state, searchFormOpen: !state.searchFormOpen };

		// togglw sidebar image
		case TOGGLE_SIDEBAR_IMAGE:
			return { ...state, enableSidebarBackgroundImage: !state.enableSidebarBackgroundImage };

		// set sidebar image
		case SET_SIDEBAR_IMAGE:
			return { ...state, selectedSidebarImage: action.payload };

		// set language
		case SET_LANGUAGE:
			return { ...state, locale: action.payload };

		// dark sidenav
		case TOGGLE_DARK_SIDENAV:
			return { ...state, isDarkSidenav: !state.isDarkSidenav };

		// agency layout bg handler
		case CHANGE_AGENCY_LAYOUT_BG:
			let layoutsBg = [];
			for (const layoutBg of state.agencyLayoutBgColors) {
				if (layoutBg.id === action.payload.id) {
					layoutBg.active = true
				} else {
					layoutBg.active = false
				}
				layoutsBg.push(layoutBg);
			}
			return {
				...state,
				agencyLayoutBgColors: layoutsBg
			}

		default: return { ...state };
	}
}
