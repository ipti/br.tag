import * as actions from './NotificationTypes';

export const getNotification = (page = 1) => ({
    type: actions.GET_NOTIFICATION,
    payload: page
});

export const getNotificationSuccess = (response) => ({
    type: actions.GET_NOTIFICATION_SUCCESS,
    payload: response.data
});

export const getNotificationFailure = (error) => ({
    type: actions.GET_NOTIFICATION_FAILURE,
    payload: error
});

export const getNotificationById = (id) => ({
    type: actions.GET_NOTIFICATION_BY_ID,
    payload: id
});

export const getNotificationByIdSuccess = (response) => ({
    type: actions.GET_NOTIFICATION_BY_ID_SUCCESS,
    payload: response.data
});

export const getNotificationByIdFailure = (error) => ({
    type: actions.GET_NOTIFICATION_BY_ID_FAILURE,
    payload: error
});

export const onChangeNotificationForm = (field) => ({
    type: actions.ON_CHANGE_NOTIFICATION_FORM,
    payload: field
});

export const onChangeNotificationFormSuccess = (field) => ({
    type: actions.ON_CHANGE_NOTIFICATION_FORM_SUCCESS,
    payload: field
});

export const saveNotification = (data) => ({
    type: actions.SAVE_NOTIFICATION,
    payload: data
});

export const saveNotificationSuccess = (response) => ({
    type: actions.SAVE_NOTIFICATION_SUCCESS,
    payload: response.data
});

export const saveNotificationFailure = (error) => ({
    type: actions.SAVE_NOTIFICATION_FAILURE,
    payload: error
});

export const updateNotification = (data) => ({
    type: actions.UPDATE_NOTIFICATION,
    payload: data
});

export const updateNotificationSuccess = (response) => ({
    type: actions.UPDATE_NOTIFICATION_SUCCESS,
    payload: response.data
});

export const updateNotificationFailure = (error) => ({
    type: actions.UPDATE_NOTIFICATION_FAILURE,
    payload: error
});