import * as actions from './MainDisplayTypes';

export const openAlert = (data) => ({
  type: actions.OPEN_ALERT,
  alertAutoHideDuration: data.autoHideDuration || null,
  alertType: data.type,
  alertMessage: data.message,
  alertHandleClose: data.handleClose || null
});

export const closeAlert = () => ({
  type: actions.CLOSE_ALERT
});
