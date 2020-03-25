import * as actions from "./registrationTypes";

export const getStudent = response => ({
  type: actions.GET_STUDENT,
  payload: response
});

export const getRegistration = response => ({
  type: actions.GET_REGISTRATION,
  payload: response
});

export const getPeriodRegistration = response => ({
  type: actions.GET_PERIOD_REGISTRATION,
  payload: response
});

export const getError = error => ({
  type: actions.GET_ERROR_REGISTRATION,
  payload: error
});

export const getSchoolList = response => ({
  type: actions.GET_SCHOOL_LIST,
  payload: response
});

export const closeAlert = () => ({
  type: actions.CLOSE_ALERT_REGISTRATION
});
