import * as actions from "../actions/schoolTypes";

const initialState = {
  schools: [],
  school: {}
};

export default (state = initialState, action) => {
  switch (action.type) {
    case actions.GET_ALL:
      return {
        schools: action.payload
      };
    case actions.GET_SCHOOL:
      return {
        school: action.payload
      };
    default:
      return state;
  }
};
