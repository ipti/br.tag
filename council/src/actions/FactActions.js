import * as actions from './FactTypes';

export const getFact = (page = 1) => ({
    type: actions.GET_FACT,
    payload: page
});

export const getFactSuccess = (response) => ({
    type: actions.GET_FACT_SUCCESS,
    payload: response.data
});

export const getFactFailure = (error) => ({
    type: actions.GET_FACT_FAILURE,
    payload: error
});

export const getFactById = (id) => ({
    type: actions.GET_FACT_BY_ID,
    payload: id
});

export const getFactByIdSuccess = (response) => ({
    type: actions.GET_FACT_BY_ID_SUCCESS,
    payload: response.data
});

export const getFactByIdFailure = (error) => ({
    type: actions.GET_FACT_BY_ID_FAILURE,
    payload: error
});

export const onChangeFactForm = (field) => ({
    type: actions.ON_CHANGE_FACT_FORM,
    payload: field
});

export const onChangeFactFormSuccess = (field) => ({
    type: actions.ON_CHANGE_FACT_FORM_SUCCESS,
    payload: field
});

export const saveFact = (data) => ({
    type: actions.SAVE_FACT,
    payload: data
});

export const saveFactSuccess = (response) => ({
    type: actions.SAVE_FACT_SUCCESS,
    payload: response.data
});

export const saveFactFailure = (error) => ({
    type: actions.SAVE_FACT_FAILURE,
    payload: error
});

export const updateFact = (data) => ({
    type: actions.UPDATE_FACT,
    payload: data
});

export const updateFactSuccess = (response) => ({
    type: actions.UPDATE_FACT_SUCCESS,
    payload: response.data
});

export const updateFactFailure = (error) => ({
    type: actions.DELETE_FACT_FAILURE,
    payload: error
});

export const deleteFact = (data, callback = null) => ({
    type: actions.DELETE_FACT,
    payload: data,
    callback: callback
});

export const deleteFactSuccess = (response) => ({
    type: actions.DELETE_FACT_SUCCESS,
    payload: response.data
});

export const deleteFactFailure = (error) => ({
    type: actions.UPDATE_FACT_FAILURE,
    payload: error
});

export const previewFact = (data) => ({
    type: actions.PREVIEW_FACT,
    payload: data
});

export const previewFactSuccess = (data) => ({
    type: actions.PREVIEW_FACT_SUCCESS,
    payload: data
});