/**
 * Feedback Sagas
 */
import { all, call, fork, put, takeLatest, takeEvery } from 'redux-saga/effects';

// api
import Api from 'Api';

import {
    GET_WARNING,
    GET_WARNING_BY_ID,
    SAVE_WARNING,
    UPDATE_WARNING,
    DELETE_WARNING,
    PREVIEW_WARNING,
    ON_CHANGE_WARNING_FORM
} from 'Actions/WarningTypes';

import {
    getWarningSuccess,
    getWarningFailure,
    getWarningByIdSuccess,
    getWarningByIdFailure,
    saveWarningSuccess,
    saveWarningFailure,
    updateWarningSuccess,
    updateWarningFailure,
    deleteWarningSuccess,
    deleteWarningFailure,
    onChangeWarningFormSuccess,
    previewWarningSuccess
} from 'Actions';


const getWarningRequest = async (page) =>
    await Api.get(`/v1/warning?page=${page}`)
        .then(response => response)
        .catch(error => error);

const getWarningByIdRequest = async (id) =>
    await Api.get(`/v1/warning/get/${id}`)
        .then(response => response)
        .catch(error => error);

const saveWarningRequest = async (data) =>
    await Api.post('/v1/warning', data)
        .then(response => response)
        .catch(error => error);

const updateWarningRequest = async (data) =>
    await Api.post(`/v1/warning/${data._id}`, data)
        .then(response => response)
        .catch(error => error);

const deleteWarningRequest = async (id) =>
    await Api.delete(`/v1/warning/${id}`)
        .then(response => response)
        .catch(error => error);


function* getWarningFromServer(action) {
    try {
        const response = yield call(() => getWarningRequest(action.payload));
        yield put(getWarningSuccess(response));
    } catch (error) {
        yield put(getWarningFailure(error));
    }
}

function* getWarningByIdFromServer(action) {
    try {
        const response = yield call(() => getWarningByIdRequest(action.payload));
        yield put(getWarningByIdSuccess(response));
    } catch (error) {
        yield put(getWarningByIdFailure(error));
    }
}

function* saveWarningFromServer(action) {
    try {
        const response = yield call(() => saveWarningRequest(action.payload));
        yield put(saveWarningSuccess(response));
    } catch (error) {
        yield put(saveWarningFailure(error));
    }
}

function* updateWarningFromServer(action) {
    try {
        const response = yield call(() => updateWarningRequest(action.payload));
        yield put(updateWarningSuccess(response));
    } catch (error) {
        yield put(updateWarningFailure(error));
    }
}

function* deleteWarningFromServer(action) {
    try {
        const response = yield call(() => deleteWarningRequest(action.payload));
        yield put(deleteWarningSuccess(response));
        if (typeof(action.callback) === 'function') {
            action.callback();
        }
    } catch (error) {
        yield put(deleteWarningFailure(error));
    }
}

function* changeWarningForm(action) {
    yield put(onChangeWarningFormSuccess(action.payload));
}

function* previewWarning(action) {
    const url = window.location;
    window.open(`${url.protocol}//${url.host}/document/preview/warning/${action.payload}`);
    yield put(previewWarningSuccess(action.payload));
}


export function* getWarning() {
    yield takeLatest(GET_WARNING, getWarningFromServer);
}

export function* getWarningById() {
    yield takeLatest(GET_WARNING_BY_ID, getWarningByIdFromServer);
}

export function* saveWarning() {
    yield takeLatest(SAVE_WARNING, saveWarningFromServer);
}

export function* updateWarning() {
    yield takeLatest(UPDATE_WARNING, updateWarningFromServer);
}

export function* deleteWarning() {
    yield takeLatest(DELETE_WARNING, deleteWarningFromServer);
}

export function* onChangeWarningForm() {
    yield takeEvery(ON_CHANGE_WARNING_FORM, changeWarningForm);
}

export function* onPreviewWarning() {
    yield takeEvery(PREVIEW_WARNING, previewWarning);
}


export default function* rootSaga() {
    yield all([
        fork(getWarning),
        fork(getWarningById),
        fork(saveWarning),
        fork(updateWarning),
        fork(deleteWarning),
        fork(onChangeWarningForm),
        fork(onPreviewWarning),
    ]);
}