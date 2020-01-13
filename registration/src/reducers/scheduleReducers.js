import * as actions from "../actions/scheduleTypes";

const initialState = {
  schedules: [],
  schedule: {}
};

export default (state = initialState, action) => {
  switch (action.type) {
    case actions.GET_ALL:
      return {
        schedules: action.payload
      };
    case actions.GET_SCHEDULE:
      return {
        schedule: action.payload
      };
    default:
      return state;
  }
};
