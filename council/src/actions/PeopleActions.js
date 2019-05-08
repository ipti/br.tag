import * as actions from './PeopleTypes';

export const getPeople = (page = 1) => ({
    type: actions.GET_PEOPLE,
    payload: page
});

export const getPeopleSuccess = (response) => ({
    type: actions.GET_PEOPLE_SUCCESS,
    payload: response.data
});

export const getPeopleFailure = (error) => ({
    type: actions.GET_PEOPLE_FAILURE,
    payload: error
});

export const getPeopleById = (id) => ({
    type: actions.GET_PEOPLE_BY_ID,
    payload: id
});

export const getPeopleByIdSuccess = (response) => ({
    type: actions.GET_PEOPLE_BY_ID_SUCCESS,
    payload: response.data
});

export const getPeopleByIdFailure = (error) => ({
    type: actions.GET_PEOPLE_BY_ID_FAILURE,
    payload: error
});

export const onChangePeopleForm = (field) => ({
    type: actions.ON_CHANGE_PEOPLE_FORM,
    payload: field
});

export const onChangePeopleFormSuccess = (field) => ({
    type: actions.ON_CHANGE_PEOPLE_FORM_SUCCESS,
    payload: field
});

export const savePeople = (data) => ({
    type: actions.SAVE_PEOPLE,
    payload: data
});

export const savePeopleSuccess = (response) => ({
    type: actions.SAVE_PEOPLE_SUCCESS,
    payload: response.data
});

export const savePeopleFailure = (error) => ({
    type: actions.SAVE_PEOPLE_FAILURE,
    payload: error
});

export const updatePeople = (data) => ({
    type: actions.UPDATE_PEOPLE,
    payload: data
});

export const updatePeopleSuccess = (response) => ({
    type: actions.UPDATE_PEOPLE_SUCCESS,
    payload: response.data
});

export const updatePeopleFailure = (error) => ({
    type: actions.UPDATE_PEOPLE_FAILURE,
    payload: error
});