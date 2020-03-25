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
  type: actions.GET_ERROR_CLASSROOM,
  payload: "Erro: " + error + ". Por favor, tente novamente."
});

export const getUpdateClassroom = data => ({
  type: actions.UPDATE_CLASSROOM,
  payload: data
});

export const getUpdateRegistration = data => ({
  type: actions.UPDATE_REGISTRATION,
  payload: data
});

export const closeAlert = () => ({
  type: actions.CLOSE_ALERT_CLASSROOM
});
