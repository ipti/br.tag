import * as actions from './HousingTypes';

export const getHousing = (page = 1) => ({
    type: actions.GET_HOUSING,
    payload: page
});

export const getHousingSuccess = (response) => ({
    type: actions.GET_HOUSING_SUCCESS,
    payload: response.data
});

export const getHousingFailure = (error) => ({
    type: actions.GET_HOUSING_FAILURE,
    payload: error
});

export const getHousingById = (id) => ({
    type: actions.GET_HOUSING_BY_ID,
    payload: id
});

export const getHousingByIdSuccess = (response) => ({
    type: actions.GET_HOUSING_BY_ID_SUCCESS,
    payload: response.data
});

export const getHousingByIdFailure = (error) => ({
    type: actions.GET_HOUSING_BY_ID_FAILURE,
    payload: error
});

export const onChangeHousingForm = (field) => ({
    type: actions.ON_CHANGE_HOUSING_FORM,
    payload: field
});

export const onChangeHousingFormSuccess = (field) => ({
    type: actions.ON_CHANGE_HOUSING_FORM_SUCCESS,
    payload: field
});

export const saveHousing = (data) => ({
    type: actions.SAVE_HOUSING,
    payload: data
});

export const saveHousingSuccess = (response) => ({
    type: actions.SAVE_HOUSING_SUCCESS,
    payload: response.data
});

export const saveHousingFailure = (error) => ({
    type: actions.SAVE_HOUSING_FAILURE,
    payload: error
});

export const updateHousing = (data) => ({
    type: actions.UPDATE_HOUSING,
    payload: data
});

export const updateHousingSuccess = (response) => ({
    type: actions.UPDATE_HOUSING_SUCCESS,
    payload: response.data
});

export const updateHousingFailure = (error) => ({
    type: actions.DELETE_HOUSING_FAILURE,
    payload: error
});

export const deleteHousing = (data, callback = null) => ({
    type: actions.DELETE_HOUSING,
    payload: data,
    callback: callback
});

export const deleteHousingSuccess = (response) => ({
    type: actions.DELETE_HOUSING_SUCCESS,
    payload: response.data
});

export const deleteHousingFailure = (error) => ({
    type: actions.UPDATE_HOUSING_FAILURE,
    payload: error
});

export const previewHousing = (data) => ({
    type: actions.PREVIEW_HOUSING,
    payload: data
});

export const previewHousingSuccess = (data) => ({
    type: actions.PREVIEW_HOUSING_SUCCESS,
    payload: data
});