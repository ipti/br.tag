import * as actions from '../actions/MainDisplayTypes';

const initialState = {
  alertOpen: false,
  alertAutoHideDuration: null,
  alertType: null,
  alertMessage: null,
  classeNames: null,
  alertHandleClose: null
};

export default (state = initialState, action) => {
  switch (action.type) {
    case actions.OPEN_ALERT:
      return {
        ...state,
        alertOpen: true,
        alertAutoHideDuration: action.alertAutoHideDuration,
        alertType: action.alertType,
        alertMessage: action.alertMessage,
        alertHandleClose: action.alertHandleClose
      };
    case actions.CLOSE_ALERT:
      return {
        ...state,
        alertOpen: false
      };
    default:
      return state;
  }
};
