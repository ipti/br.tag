import * as actions from "../actions/registrationTypes";

const initialState = {
  student: {},
  schools: {},
  registration: {},
  period: {},
  msgError: "",
  schoolList: [],
  loading: true,
  openAlert: false
};

export default (state = initialState, action) => {
  switch (action.type) {
    case actions.GET_STUDENT:
      return {
        student: action.payload,
        loading: false,
        openAlert: action?.payload?.status === "0" ? true : false
      };
    case actions.FETCH_STUDENT:
      return {
        student: action.payload,
        loading: true
      };
    case actions.FETCH_SCHOOLS_LIST:
      return {
        ...state,
        loading: true
      };
    case actions.FETCH_SAVE_REGISTRATION:
      return {
        ...state,
        loading: true
      };
    case actions.GET_REGISTRATION:
      return {
        registration: action.payload,
        loading: false
      };
    case actions.GET_PERIOD_REGISTRATION:
      return {
        period: action.payload
      };
    case actions.GET_ERROR_REGISTRATION:
      return {
        ...state,
        msgError: action.payload,
        loading: false
      };
    case actions.GET_SCHOOL_LIST:
      return {
        ...state,
        schoolList: action.payload,
        loading: false
      };
    case actions.CLOSE_ALERT_REGISTRATION:
      return {
        ...state,
        openAlert: false
      };
    default:
      return state;
  }
};
