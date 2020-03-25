import * as actions from "../actions/classroomTypes";

const initialState = {
  classrooms: [],
  classroom: {},
  registration: {},
  fetchRegistration: {},
  fetchClassroom: {},
  msgError: "",
  loading: true,
  isRedirectClassroom: false,
  isRedirectRegistration: false
};

export default (state = initialState, action) => {
  switch (action.type) {
    case actions.FETCH_CLASSROOMS:
      return {
        ...state,
        loading: true
      };
    case actions.FETCH_CLASSROOMS_PAGE:
      return {
        ...state,
        loading: true
      };
    case actions.GET_ALL:
      return {
        ...state,
        classrooms: action.payload,
        loading: false,
        isRedirectClassroom: false,
        isRedirectRegistration: false
      };
    case actions.FETCH_CLASSROOM:
      return {
        ...state,
        loading: true
      };
    case actions.GET_CLASSROOM:
      return {
        ...state,
        classroom: action.payload,
        loading: false,
        isRedirectRegistration: false
      };
    case actions.FETCH_UPDATE_CLASSROOM:
      return {
        ...state,
        loading: true,
        openAlert: true
      };
    case actions.CLOSE_ALERT_CLASSROOM:
      return {
        ...state,
        openAlert: false,
        fetchClassroom: {},
        fetchRegistration: {}
      };
    case actions.UPDATE_CLASSROOM:
      return {
        ...state,
        fetchClassroom: action.payload,
        loading: false,
        isRedirectClassroom: true
      };
    case actions.FETCH_REGISTRATION:
      return {
        ...state,
        loading: true
      };
    case actions.GET_REGISTRATION:
      return {
        ...state,
        registration: action.payload,
        loading: false,
        isRedirectClassroom: false,
        isRedirectRegistration: false
      };
    case actions.FETCH_UPDATE_REGISTRATION:
      return {
        ...state,
        loading: true,
        openAlert: true
      };
    case actions.UPDATE_REGISTRATION:
      return {
        ...state,
        fetchRegistration: action.payload,
        loading: false,
        isRedirectRegistration: true
      };
    case actions.GET_ERROR_CLASSROOM:
      return {
        ...state,
        msgError: action.payload,
        loading: false
      };
    default:
      return state;
  }
};
