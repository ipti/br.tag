import * as actions from "../actions/schoolTypes";

const initialState = {
  schools: [],
  school: {},
  msgError: "",
  loading: true,
  openAlert: false
};

export default (state = initialState, action) => {
  switch (action.type) {
    case actions.FETCH_SCHOOLS:
      return {
        ...state,
        loading: true
      };
    case actions.FETCH_SCHOOLS_PAGE:
      return {
        ...state,
        loading: true
      };
    case actions.GET_ALL:
      return {
        schools: action.payload,
        loading: false
      };
    case actions.CLOSE_ALERT_SCHOOL:
      return {
        openAlert: false
      };
    case actions.FETCH_SCHOOL:
      return {
        ...state,
        loading: true
      };
    case actions.GET_SCHOOL:
      return {
        school: action.payload,
        loading: false
      };
    case actions.GET_ERROR_SCHOOL:
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
