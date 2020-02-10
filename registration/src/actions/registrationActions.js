import * as actions from "./registrationTypes";

export const getStudent = response => ({
  type: actions.GET_STUDENT,
  payload: response
});

export const getRegistration = response => ({
  type: actions.GET_REGISTRATION,
  payload: response
});

export const getError = error => ({
  type: actions.GET_ERROR,
  payload: error
});
