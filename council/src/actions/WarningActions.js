import * as actions from './WarningTypes';

export const getWarning = (page = 1) => ({
    type: actions.GET_WARNING,
    payload: page
});

export const getWarningSuccess = (response) => ({
    type: actions.GET_WARNING_SUCCESS,
    payload: response.data
});

export const getWarningFailure = (error) => ({
    type: actions.GET_WARNING_FAILURE,
    payload: error
});

export const getWarningById = (id) => ({
    type: actions.GET_WARNING_BY_ID,
    payload: id
});

export const getWarningByIdSuccess = (response) => ({
    type: actions.GET_WARNING_BY_ID_SUCCESS,
    payload: response.data
});

export const getWarningByIdFailure = (error) => ({
    type: actions.GET_WARNING_BY_ID_FAILURE,
    payload: error
});

export const onChangeWarningForm = (field) => ({
    type: actions.ON_CHANGE_WARNING_FORM,
    payload: field
});

export const onChangeWarningFormSuccess = (field) => ({
    type: actions.ON_CHANGE_WARNING_FORM_SUCCESS,
    payload: field
});

export const saveWarning = (data) => ({
    type: actions.SAVE_WARNING,
    payload: data
});

export const saveWarningSuccess = (response) => ({
    type: actions.SAVE_WARNING_SUCCESS,
    payload: response.data
});

export const saveWarningFailure = (error) => ({
    type: actions.SAVE_WARNING_FAILURE,
    payload: error
});

export const updateWarning = (data) => ({
    type: actions.UPDATE_WARNING,
    payload: data
});

export const updateWarningSuccess = (response) => ({
    type: actions.UPDATE_WARNING_SUCCESS,
    payload: response.data
});

export const updateWarningFailure = (error) => ({
    type: actions.DELETE_WARNING_FAILURE,
    payload: error
});

export const deleteWarning = (data, callback = null) => ({
    type: actions.DELETE_WARNING,
    payload: data,
    callback: callback
});

export const deleteWarningSuccess = (response) => ({
    type: actions.DELETE_WARNING_SUCCESS,
    payload: response.data
});

export const deleteWarningFailure = (error) => ({
    type: actions.UPDATE_WARNING_FAILURE,
    payload: error
});

export const previewWarning = (data) => ({
    type: actions.PREVIEW_WARNING,
    payload: data
});

export const previewWarningSuccess = (data) => ({
    type: actions.PREVIEW_WARNING_SUCCESS,
    payload: data
});