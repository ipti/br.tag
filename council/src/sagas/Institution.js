/**
 * Feedback Sagas
 */
import { all, call, fork, put, takeLatest, takeEvery } from 'redux-saga/effects';

// api
import Api from 'Api';

import {
    GET_INSTITUTION,
    GET_INSTITUTION_BY_ID,
    SAVE_INSTITUTION,
    UPDATE_INSTITUTION,
    ON_CHANGE_INSTITUTION_FORM
} from 'Actions/InstitutionTypes';

import {
    getInstitutionSuccess,
    getInstitutionFailure,
    getInstitutionByIdSuccess,
    getInstitutionByIdFailure,
    saveInstitutionSuccess,
    saveInstitutionFailure,
    updateInstitutionSuccess,
    updateInstitutionFailure,
    onChangeInstitutionFormSuccess,
} from 'Actions';


const getInstitutionRequest = async (page) =>
    await Api.get(`/v1/institution?page=${page}`)
        .then(response => response)
        .catch(error => error);

const getInstitutionByIdRequest = async (id) =>
    await Api.get(`/v1/institution/get/${id}`)
        .then(response => response)
        .catch(error => error);

const saveInstitutionRequest = async (data) =>
    await Api.post('/v1/institution', data)
        .then(response => response)
        .catch(error => error);

const updateInstitutionRequest = async (data) =>
    await Api.post(`/v1/institution/${data._id}`, data)
        .then(response => response)
        .catch(error => error);


function* getInstitutionFromServer(action) {
    try {
        const response = yield call(() => getInstitutionRequest(action.payload));
        yield put(getInstitutionSuccess(response));
    } catch (error) {
        yield put(getInstitutionFailure(error));
    }
}

function* getInstitutionByIdFromServer(action) {
    try {
        const response = yield call(() => getInstitutionByIdRequest(action.payload));
        yield put(getInstitutionByIdSuccess(response));
    } catch (error) {
        yield put(getInstitutionByIdFailure(error));
    }
}

function* saveInstitutionFromServer(action) {
    try {
        const response = yield call(() => saveInstitutionRequest(action.payload));
        yield put(saveInstitutionSuccess(response));
    } catch (error) {
        yield put(saveInstitutionFailure(error));
    }
}

function* updateInstitutionFromServer(action) {
    try {
        const response = yield call(() => updateInstitutionRequest(action.payload));
        yield put(updateInstitutionSuccess(response));
    } catch (error) {
        yield put(updateInstitutionFailure(error));
    }
}

function* changeInstitutionForm(action) {
    yield put(onChangeInstitutionFormSuccess(action.payload));
}


export function* getInstitution() {
    yield takeLatest(GET_INSTITUTION, getInstitutionFromServer);
}

export function* getInstitutionById() {
    yield takeLatest(GET_INSTITUTION_BY_ID, getInstitutionByIdFromServer);
}

export function* saveInstitution() {
    yield takeLatest(SAVE_INSTITUTION, saveInstitutionFromServer);
}

export function* updateInstitution() {
    yield takeLatest(UPDATE_INSTITUTION, updateInstitutionFromServer);
}

export function* onChangeInstitutionForm() {
    yield takeEvery(ON_CHANGE_INSTITUTION_FORM, changeInstitutionForm);
}


export default function* rootSaga() {
    yield all([
        fork(getInstitution),
        fork(getInstitutionById),
        fork(saveInstitution),
        fork(updateInstitution),
        fork(onChangeInstitutionForm),
    ]);
}