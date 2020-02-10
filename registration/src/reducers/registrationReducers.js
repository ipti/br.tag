import * as actions from "../actions/registrationTypes";

const initialState = {
  student: {},
  schools: {},
  registration: {}
};

export default (state = initialState, action) => {
  switch (action.type) {
    case actions.GET_STUDENT:
      return {
        student: action.payload
      };
    case actions.GET_REGISTRATION:
      return {
        registration: action.payload
      };
    default:
      return state;
  }
};
