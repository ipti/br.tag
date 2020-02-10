import * as actions from "../actions/scheduleTypes";

const initialState = {
  schedules: [],
  schedule: {},
  fetchSchedule: {}
};

export default (state = initialState, action) => {
  switch (action.type) {
    case actions.GET_ALL:
      return {
        ...state,
        schedules: action.payload
      };
    case actions.GET_SCHEDULE:
      return {
        ...state,
        schedule: action.payload
      };
    case actions.UPDATE_SCHEDULE:
      return {
        ...state,
        fetchSchedule: action.payload
      };
    default:
      return state;
  }
};
