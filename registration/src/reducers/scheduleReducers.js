import * as actions from "../actions/scheduleTypes";

const initialState = {
  schedules: [],
  schedule: {},
  fetchSchedule: {},
  msgError: "",
  openAlert: false,
  loading: true
};

export default (state = initialState, action) => {
  switch (action.type) {
    case actions.FETCH_SCHEDULES:
      return {
        ...state,
        loading: true
      };
    case actions.FETCH_SCHEDULES_PAGE:
      return {
        ...state,
        loading: true
      };
    case actions.GET_ALL:
      return {
        ...state,
        schedules: action.payload,
        loading: false
      };
    case actions.FETCH_SCHEDULE:
      return {
        ...state,
        loading: true
      };
    case actions.GET_SCHEDULE:
      return {
        ...state,
        schedule: action.payload,
        loading: false
      };
    case actions.FETCH_SAVE_SCHEDULE:
      return {
        ...state,
        loading: true,
        openAlert: true
      };
    case actions.GET_SCHEDULE_SAVE:
      return {
        ...state,
        fetchSchedule: action.payload,
        loading: false
      };
    case actions.CLOSE_ALERT_SCHEDULE:
      return {
        ...state,
        openAlert: false
      };
    case actions.FETCH_UPDATE_SCHEDULE:
      return {
        ...state,
        loading: true,
        openAlert: true
      };
    case actions.UPDATE_SCHEDULE:
      return {
        ...state,
        fetchSchedule: action.payload,
        loading: false
      };
    case actions.GET_ERROR_SCHEDULE:
      return {
        ...state,
        msgError: action.payload,
        loading: false,
        openAlert: true
      };
    default:
      return state;
  }
};
