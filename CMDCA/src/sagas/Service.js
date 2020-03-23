/**
 * Feedback Sagas
 */
import { all, call, fork, put, takeLatest, takeEvery } from 'redux-saga/effects';

// api
import Api from 'Api';

import {
    GET_SERVICE,
    GET_SERVICE_BY_ID,
    SAVE_SERVICE,
    UPDATE_SERVICE,
    DELETE_SERVICE,
    PREVIEW_SERVICE,
    ON_CHANGE_SERVICE_FORM
} from 'Actions/ServiceTypes';

import {
    getServiceSuccess,
    getServiceFailure,
    getServiceByIdSuccess,
    getServiceByIdFailure,
    saveServiceSuccess,
    saveServiceFailure,
    updateServiceSuccess,
    updateServiceFailure,
    deleteServiceSuccess,
    deleteServiceFailure,
    onChangeServiceFormSuccess,
    previewServiceSuccess
} from 'Actions';


const getServiceRequest = async (page) =>
    await Api.get(`/v1/service?page=${page}`)
        .then(response => response)
        .catch(error => Promise.reject(error));

const getServiceByIdRequest = async (id) =>
    await Api.get(`/v1/service/get/${id}`)
        .then(response => response)
        .catch(error => Promise.reject(error));

const saveServiceRequest = async (data) =>
    await Api.post('/v1/service', data)
        .then(response => response)
        .catch(error => Promise.reject(error));

const updateServiceRequest = async (data) =>
    await Api.post(`/v1/service/${data._id}`, data)
        .then(response => response)
        .catch(error => Promise.reject(error));

const deleteServiceRequest = async (id) => 
    await Api.delete(`/v1/service/${id}`)
        .then(response => response)
        .catch(error => Promise.reject(error));


function* getServiceFromServer(action) {
    try {
        const response = yield call(() => getServiceRequest(action.payload));
        yield put(getServiceSuccess(response));
    } catch (error) {
        yield put(getServiceFailure(error));
    }
}

function* getServiceByIdFromServer(action) {
    try {
        const response = yield call(() => getServiceByIdRequest(action.payload));
        yield put(getServiceByIdSuccess(response));
    } catch (error) {
        yield put(getServiceByIdFailure(error));
    }
}

function* saveServiceFromServer(action) {
    try {
        const response = yield call(() => saveServiceRequest(action.payload));
        yield put(saveServiceSuccess(response));
    } catch (error) {
        yield put(saveServiceFailure(error));
    }
}

function* updateServiceFromServer(action) {
    try {
        const response = yield call(() => updateServiceRequest(action.payload));
        yield put(updateServiceSuccess(response));
    } catch (error) {
        yield put(updateServiceFailure(error));
    }
}

function* deleteServiceFromServer(action) {
    try {
        const response = yield call(() => deleteServiceRequest(action.payload));
        yield put(deleteServiceSuccess(response));

        if (typeof(action.callback) === 'function') {
            action.callback();
        }
    } catch (error) {
        yield put(deleteServiceFailure(error));
    }
}

function* changeServiceForm(action) {
    yield put(onChangeServiceFormSuccess(action.payload));
}

function* previewService(action) {
    const url = window.location;
    window.open(`${url.protocol}//${url.host}/document/preview/service/${action.payload}`);
    yield put(previewServiceSuccess(action.payload));
}


export function* getService() {
    yield takeLatest(GET_SERVICE, getServiceFromServer);
}

export function* getServiceById() {
    yield takeLatest(GET_SERVICE_BY_ID, getServiceByIdFromServer);
}

export function* saveService() {
    yield takeLatest(SAVE_SERVICE, saveServiceFromServer);
}

export function* updateService() {
    yield takeLatest(UPDATE_SERVICE, updateServiceFromServer);
}

export function* deleteService() {
    yield takeLatest(DELETE_SERVICE, deleteServiceFromServer);
}

export function* onChangeServiceForm() {
    yield takeEvery(ON_CHANGE_SERVICE_FORM, changeServiceForm);
}

export function* onPreviewService() {
    yield takeEvery(PREVIEW_SERVICE, previewService);
}


export default function* rootSaga() {
    yield all([
        fork(getService),
        fork(getServiceById),
        fork(saveService),
        fork(updateService),
        fork(deleteService),
        fork(onChangeServiceForm),
        fork(onPreviewService),
    ]);
}