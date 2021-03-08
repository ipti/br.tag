import * as actions from '../actions/FollowTypes';

const initialState = {
  students: [],
  loadingDashboard: true,
  error: {}
};

export default (state = initialState, action) => {
  switch (action.type) {
    case actions.GET_STUDENTS:
      return {
        ...state,
        students: action.payload
      };
    // case actions.GET_ERROR:
    //   return { ...state, error: action.payload };
    // case actions.SET_LOADING:
    //   return { ...state, loadingDashboard: action.payload };
    default:
      return state;
  }
};
