/**
 * Feedback Sagas
 */
import { all, call, fork, put, takeLatest, takeEvery } from 'redux-saga/effects';

// api
import Api from 'Api';

import {
    GET_NOTIFICATION,
    GET_NOTIFICATION_BY_ID,
    SAVE_NOTIFICATION,
    UPDATE_NOTIFICATION,
    DELETE_NOTIFICATION,
    PREVIEW_NOTIFICATION,
    ON_CHANGE_NOTIFICATION_FORM
} from 'Actions/NotificationTypes';

import {
    getNotificationSuccess,
    getNotificationFailure,
    getNotificationByIdSuccess,
    getNotificationByIdFailure,
    saveNotificationSuccess,
    saveNotificationFailure,
    updateNotificationSuccess,
    updateNotificationFailure,
    deleteNotificationSuccess,
    deleteNotificationFailure,
    onChangeNotificationFormSuccess,
    previewNotificationSuccess
} from 'Actions';


const getNotificationRequest = async (page) =>
    await Api.get(`/v1/notification?page=${page}`)
        .then(response => response)
        .catch(error => Promise.reject(error));

const getNotificationByIdRequest = async (id) =>
    await Api.get(`/v1/notification/get/${id}`)
        .then(response => response)
        .catch(error => Promise.reject(error));

const saveNotificationRequest = async (data) =>
    await Api.post('/v1/notification', data)
        .then(response => response)
        .catch(error => Promise.reject(error));

const updateNotificationRequest = async (data) =>
    await Api.post(`/v1/notification/${data._id}`, data)
        .then(response => response)
        .catch(error => Promise.reject(error));

const deleteNotificationRequest = async (id) =>
    await Api.delete(`/v1/notification/${id}`)
        .then(response => response)
        .catch(error => Promise.reject(error));


function* getNotificationFromServer(action) {
    try {
        const response = yield call(() => getNotificationRequest(action.payload));
        yield put(getNotificationSuccess(response));
    } catch (error) {
        yield put(getNotificationFailure(error));
    }
}

function* getNotificationByIdFromServer(action) {
    try {
        const response = yield call(() => getNotificationByIdRequest(action.payload));
        yield put(getNotificationByIdSuccess(response));
    } catch (error) {
        yield put(getNotificationByIdFailure(error));
    }
}

function* saveNotificationFromServer(action) {
    try {
        const response = yield call(() => saveNotificationRequest(action.payload));
        yield put(saveNotificationSuccess(response));
    } catch (error) {
        yield put(saveNotificationFailure(error));
    }
}

function* updateNotificationFromServer(action) {
    try {
        const response = yield call(() => updateNotificationRequest(action.payload));
        yield put(updateNotificationSuccess(response));
    } catch (error) {
        yield put(updateNotificationFailure(error));
    }
}

function* deleteNotificationFromServer(action) {
    try {
        const response = yield call(() => deleteNotificationRequest(action.payload));
        yield put(deleteNotificationSuccess(response));
        if (typeof(action.callback) === 'function') {
            action.callback();
        }
    } catch (error) {
        yield put(deleteNotificationFailure(error));
    }
}

function* changeNotificationForm(action) {
    yield put(onChangeNotificationFormSuccess(action.payload));
}

function* previewNotification(action) {
    const url = window.location;
    window.open(`${url.protocol}//${url.host}/document/preview/notification/${action.payload}`);
    yield put(previewNotificationSuccess(action.payload));
}


export function* getNotification() {
    yield takeLatest(GET_NOTIFICATION, getNotificationFromServer);
}

export function* getNotificationById() {
    yield takeLatest(GET_NOTIFICATION_BY_ID, getNotificationByIdFromServer);
}

export function* saveNotification() {
    yield takeLatest(SAVE_NOTIFICATION, saveNotificationFromServer);
}

export function* updateNotification() {
    yield takeLatest(UPDATE_NOTIFICATION, updateNotificationFromServer);
}

export function* deleteNotification() {
    yield takeLatest(DELETE_NOTIFICATION, deleteNotificationFromServer);
}

export function* onChangeNotificationForm() {
    yield takeEvery(ON_CHANGE_NOTIFICATION_FORM, changeNotificationForm);
}

export function* onPreviewNotification() {
    yield takeEvery(PREVIEW_NOTIFICATION, previewNotification);
}


export default function* rootSaga() {
    yield all([
        fork(getNotification),
        fork(getNotificationById),
        fork(saveNotification),
        fork(updateNotification),
        fork(deleteNotification),
        fork(onChangeNotificationForm),
        fork(onPreviewNotification),
    ]);
}