import * as actions from "../actions/classroomTypes";

const initialState = {
  classrooms: [],
  classroom: {},
  registration: {},
  fetchRegistration: {},
  fetchClassroom: {}
};

export default (state = initialState, action) => {
  switch (action.type) {
    case actions.GET_ALL:
      return {
        ...state,
        classrooms: action.payload
      };
    case actions.GET_CLASSROOM:
      return {
        ...state,
        classroom: action.payload
      };
    case actions.UPDATE_CLASSROOM:
      return {
        ...state,
        fetchClassroom: action.payload
      };
    case actions.GET_REGISTRATION:
      return {
        ...state,
        registration: action.payload
      };
    case actions.UPDATE_REGISTRATION:
      return {
        ...state,
        fetchRegistration: action.payload
      };
    default:
      return state;
  }
};
