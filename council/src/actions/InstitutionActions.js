import * as actions from './InstitutionTypes';

export const getInstitution = (page = 1) => ({
    type: actions.GET_INSTITUTION,
    payload: page
});

export const getInstitutionSuccess = (response) => ({
    type: actions.GET_INSTITUTION_SUCCESS,
    payload: response.data
});

export const getInstitutionFailure = (error) => ({
    type: actions.GET_INSTITUTION_FAILURE,
    payload: error
});

export const getInstitutionById = (id) => ({
    type: actions.GET_INSTITUTION_BY_ID,
    payload: id
});

export const getInstitutionByIdSuccess = (response) => ({
    type: actions.GET_INSTITUTION_BY_ID_SUCCESS,
    payload: response.data
});

export const getInstitutionByIdFailure = (error) => ({
    type: actions.GET_INSTITUTION_BY_ID_FAILURE,
    payload: error
});

export const onChangeInstitutionForm = (field) => ({
    type: actions.ON_CHANGE_INSTITUTION_FORM,
    payload: field
});

export const onChangeInstitutionFormSuccess = (field) => ({
    type: actions.ON_CHANGE_INSTITUTION_FORM_SUCCESS,
    payload: field
});

export const saveInstitution = (data) => ({
    type: actions.SAVE_INSTITUTION,
    payload: data
});

export const saveInstitutionSuccess = (response) => ({
    type: actions.SAVE_INSTITUTION_SUCCESS,
    payload: response.data
});

export const saveInstitutionFailure = (error) => ({
    type: actions.SAVE_INSTITUTION_FAILURE,
    payload: error
});

export const updateInstitution = (data) => ({
    type: actions.UPDATE_INSTITUTION,
    payload: data
});

export const updateInstitutionSuccess = (response) => ({
    type: actions.UPDATE_INSTITUTION_SUCCESS,
    payload: response.data
});

export const updateInstitutionFailure = (error) => ({
    type: actions.UPDATE_INSTITUTION_FAILURE,
    payload: error
});