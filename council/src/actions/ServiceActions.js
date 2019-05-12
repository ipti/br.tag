import * as actions from './ServiceTypes';

export const getService = (page = 1) => ({
    type: actions.GET_SERVICE,
    payload: page
});

export const getServiceSuccess = (response) => ({
    type: actions.GET_SERVICE_SUCCESS,
    payload: response.data
});

export const getServiceFailure = (error) => ({
    type: actions.GET_SERVICE_FAILURE,
    payload: error
});

export const getServiceById = (id) => ({
    type: actions.GET_SERVICE_BY_ID,
    payload: id
});

export const getServiceByIdSuccess = (response) => ({
    type: actions.GET_SERVICE_BY_ID_SUCCESS,
    payload: response.data
});

export const getServiceByIdFailure = (error) => ({
    type: actions.GET_SERVICE_BY_ID_FAILURE,
    payload: error
});

export const onChangeServiceForm = (field) => ({
    type: actions.ON_CHANGE_SERVICE_FORM,
    payload: field
});

export const onChangeServiceFormSuccess = (field) => ({
    type: actions.ON_CHANGE_SERVICE_FORM_SUCCESS,
    payload: field
});

export const saveService = (data) => ({
    type: actions.SAVE_SERVICE,
    payload: data
});

export const saveServiceSuccess = (response) => ({
    type: actions.SAVE_SERVICE_SUCCESS,
    payload: response.data
});

export const saveServiceFailure = (error) => ({
    type: actions.SAVE_SERVICE_FAILURE,
    payload: error
});

export const updateService = (data) => ({
    type: actions.UPDATE_SERVICE,
    payload: data
});

export const updateServiceSuccess = (response) => ({
    type: actions.UPDATE_SERVICE_SUCCESS,
    payload: response.data
});

export const updateServiceFailure = (error) => ({
    type: actions.DELETE_SERVICE_FAILURE,
    payload: error
});

export const deleteService = (data) => ({
    type: actions.DELETE_SERVICE,
    payload: data
});

export const deleteServiceSuccess = (response) => ({
    type: actions.DELETE_SERVICE_SUCCESS,
    payload: response.data
});

export const deleteServiceFailure = (error) => ({
    type: actions.UPDATE_SERVICE_FAILURE,
    payload: error
});

export const previewService = (data) => ({
    type: actions.PREVIEW_SERVICE,
    payload: data
});

export const previewServiceSuccess = (data) => ({
    type: actions.PREVIEW_SERVICE_SUCCESS,
    payload: data
});