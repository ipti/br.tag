import * as actions from "./classroomTypes";

export const getClassrooms = response => ({
  type: actions.GET_ALL,
  payload: response
});

export const getClassroom = response => ({
  type: actions.GET_CLASSROOM,
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

export const getUpdateClassroom = data => ({
  type: actions.UPDATE_CLASSROOM,
  payload: data
});

export const getUpdateRegistration = data => ({
  type: actions.UPDATE_REGISTRATION,
  payload: data
});
