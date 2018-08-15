/**
 * Sidebar Reducers
 */
import update from 'react-addons-update';
import { TOGGLE_MENU, AGENCY_TOGGLE_MENU } from 'Actions/types';

// nav links
import navLinks from 'Components/Sidebar/NavLinks';
import agencyNavLinks from 'Components/AgencyMenu/NavLinks';

const INIT_STATE = {
	sidebarMenus: navLinks,
	agencySidebarMenu: agencyNavLinks,
};

export default (state = INIT_STATE, action) => {
	switch (action.type) {
		case TOGGLE_MENU:
			let index = state.sidebarMenus[action.payload.stateCategory].indexOf(action.payload.menu);
			for (var key in state.sidebarMenus) {
				var obj = state.sidebarMenus[key];
				for (let i = 0; i < obj.length; i++) {
					const element = obj[i];
					if (element.open) {
						if (key === action.payload.stateCategory) {
							return update(state, {
								sidebarMenus: {
									[key]: {
										[i]: {
											open: { $set: false }
										},
										[index]: {
											open: { $set: !action.payload.menu.open }
										}
									}
								}
							});
						} else {
							return update(state, {
								sidebarMenus: {
									[key]: {
										[i]: {
											open: { $set: false }
										}
									},
									[action.payload.stateCategory]: {
										[index]: {
											open: { $set: !action.payload.menu.open }
										}
									}
								}
							});
						}
					}
				}
			}
			return update(state, {
				sidebarMenus: {
					[action.payload.stateCategory]: {
						[index]: {
							open: { $set: !action.payload.menu.open }
						}
					}
				}
			});
		case AGENCY_TOGGLE_MENU:
			let agencyMenuIndex = state.agencySidebarMenu[action.payload.stateCategory].indexOf(action.payload.menu);
			for (var id in state.agencySidebarMenu) {
				var object = state.agencySidebarMenu[id];
				for (let i = 0; i < object.length; i++) {
					const element = object[i];
					if (element.open) {
						if (id === action.payload.stateCategory) {
							return update(state, {
								agencySidebarMenu: {
									[id]: {
										[i]: {
											open: { $set: false }
										},
										[agencyMenuIndex]: {
											open: { $set: !action.payload.menu.open }
										}
									}
								}
							});
						} else {
							return update(state, {
								agencySidebarMenu: {
									[id]: {
										[i]: {
											open: { $set: false }
										}
									},
									[action.payload.stateCategory]: {
										[agencyMenuIndex]: {
											open: { $set: !action.payload.menu.open }
										}
									}
								}
							});
						}
					}
				}
			}
			return update(state, {
				agencySidebarMenu: {
					[action.payload.stateCategory]: {
						[agencyMenuIndex]: {
							open: { $set: !action.payload.menu.open }
						}
					}
				}
			});
		default:
			return { ...state };
	}
}
