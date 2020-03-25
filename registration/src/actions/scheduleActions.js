import * as actions from "./scheduleTypes";

export const getSchedules = response => ({
  type: actions.GET_ALL,
  payload: response
});

export const getSchedule = response => ({
  type: actions.GET_SCHEDULE,
  payload: response
});

export const getSaveSchedule = response => ({
  type: actions.GET_SCHEDULE_SAVE,
  payload: response
});

export const getError = error => ({
  type: actions.GET_ERROR_SCHEDULE,
  payload: "Erro: " + error + ". Por favor, tente novamente."
});

export const getUpdateSchedule = data => ({
  type: actions.UPDATE_SCHEDULE,
  payload: data
});

export const closeAlert = () => ({
  type: actions.CLOSE_ALERT_SCHEDULE
});
