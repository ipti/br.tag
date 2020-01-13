import * as actions from "./scheduleTypes";

export const getSchedules = response => ({
  type: actions.GET_ALL,
  payload: response
});

export const getSchedule = response => ({
  type: actions.GET_SCHEDULE,
  payload: response
});

export const getError = error => ({
  type: actions.GET_ERROR,
  payload: error
});
