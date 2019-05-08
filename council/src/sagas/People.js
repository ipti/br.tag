/**
 * Feedback Sagas
 */
import { all, call, fork, put, takeLatest, takeEvery } from 'redux-saga/effects';

// api
import Api from 'Api';

import {
    GET_PEOPLE,
    GET_PEOPLE_BY_ID,
    SAVE_PEOPLE,
    UPDATE_PEOPLE,
    ON_CHANGE_PEOPLE_FORM
} from 'Actions/PeopleTypes';

import {
    getPeopleSuccess,
    getPeopleFailure,
    getPeopleByIdSuccess,
    getPeopleByIdFailure,
    savePeopleSuccess,
    savePeopleFailure,
    updatePeopleSuccess,
    updatePeopleFailure,
    onChangePeopleFormSuccess,
} from 'Actions';


const getPeopleRequest = async (page) =>
    await Api.get(`/v1/people?page=${page}`)
        .then(response => response)
        .catch(error => error);

const getPeopleByIdRequest = async (id) =>
    await Api.get(`/v1/people/get/${id}`)
        .then(response => response)
        .catch(error => error);

const savePeopleRequest = async (data) =>
    await Api.post('/v1/people', data)
        .then(response => response)
        .catch(error => error);

const updatePeopleRequest = async (data) =>
    await Api.post(`/v1/people/${data._id}`, data)
        .then(response => response)
        .catch(error => error);


function* getPeopleFromServer(action) {
    try {
        const response = yield call(() => getPeopleRequest(action.payload));
        yield put(getPeopleSuccess(response));
    } catch (error) {
        yield put(getPeopleFailure(error));
    }
}

function* getPeopleByIdFromServer(action) {
    try {
        const response = yield call(() => getPeopleByIdRequest(action.payload));
        yield put(getPeopleByIdSuccess(response));
    } catch (error) {
        yield put(getPeopleByIdFailure(error));
    }
}

function* savePeopleFromServer(action) {
    try {
        const response = yield call(() => savePeopleRequest(action.payload));
        yield put(savePeopleSuccess(response));
    } catch (error) {
        yield put(savePeopleFailure(error));
    }
}

function* updatePeopleFromServer(action) {
    try {
        const response = yield call(() => updatePeopleRequest(action.payload));
        yield put(updatePeopleSuccess(response));
    } catch (error) {
        yield put(updatePeopleFailure(error));
    }
}

function* changePeopleForm(action) {
    yield put(onChangePeopleFormSuccess(action.payload));
}


export function* getPeople() {
    yield takeLatest(GET_PEOPLE, getPeopleFromServer);
}

export function* getPeopleById() {
    yield takeLatest(GET_PEOPLE_BY_ID, getPeopleByIdFromServer);
}

export function* savePeople() {
    yield takeLatest(SAVE_PEOPLE, savePeopleFromServer);
}

export function* updatePeople() {
    yield takeLatest(UPDATE_PEOPLE, updatePeopleFromServer);
}

export function* onChangePeopleForm() {
    yield takeEvery(ON_CHANGE_PEOPLE_FORM, changePeopleForm);
}


export default function* rootSaga() {
    yield all([
        fork(getPeople),
        fork(getPeopleById),
        fork(savePeople),
        fork(updatePeople),
        fork(onChangePeopleForm),
    ]);
}