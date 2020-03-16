import * as actions from "../actions/registrationTypes";

const initialState = {
  student: {},
  schools: {},
  registration: {},
  period: {},
  msgError: ""
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
    case actions.GET_PERIOD_REGISTRATION:
      return {
        period: action.payload
      };
    case actions.GET_ERROR_REGISTRATION:
      return {
        ...state,
        msgError: action.payload
      };
    default:
      return state;
  }
};
