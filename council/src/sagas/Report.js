/**
 * Feedback Sagas
 */
import { all, call, fork, put, takeLatest, takeEvery } from 'redux-saga/effects';

// api
import Api from 'Api';

import {
    GET_REPORT,
    GET_REPORT_BY_ID,
    SAVE_REPORT,
    UPDATE_REPORT,
    DELETE_REPORT,
    PREVIEW_REPORT,
    ON_CHANGE_REPORT_FORM
} from 'Actions/ReportTypes';

import {
    getReportSuccess,
    getReportFailure,
    getReportByIdSuccess,
    getReportByIdFailure,
    saveReportSuccess,
    saveReportFailure,
    updateReportSuccess,
    updateReportFailure,
    deleteReportSuccess,
    deleteReportFailure,
    onChangeReportFormSuccess,
    previewReportSuccess
} from 'Actions';


const getReportRequest = async (page) =>
    await Api.get(`/v1/report?page=${page}`)
        .then(response => response)
        .catch(error => error);

const getReportByIdRequest = async (id) =>
    await Api.get(`/v1/report/get/${id}`)
        .then(response => response)
        .catch(error => error);

const saveReportRequest = async (data) =>
    await Api.post('/v1/report', data)
        .then(response => response)
        .catch(error => error);

const updateReportRequest = async (data) =>
    await Api.post(`/v1/report/${data._id}`, data)
        .then(response => response)
        .catch(error => error);

const deleteReportRequest = async (id) => {
    await Api.delete(`/v1/report/${id}`)
        .then(response => response)
        .catch(error => error);

}


function* getReportFromServer(action) {
    try {
        const response = yield call(() => getReportRequest(action.payload));
        yield put(getReportSuccess(response));
    } catch (error) {
        yield put(getReportFailure(error));
    }
}

function* getReportByIdFromServer(action) {
    try {
        const response = yield call(() => getReportByIdRequest(action.payload));
        yield put(getReportByIdSuccess(response));
    } catch (error) {
        yield put(getReportByIdFailure(error));
    }
}

function* saveReportFromServer(action) {
    console.log(action.payload)
    try {
        const response = yield call(() => saveReportRequest(action.payload));
        yield put(saveReportSuccess(response));
    } catch (error) {
        yield put(saveReportFailure(error));
    }
}

function* updateReportFromServer(action) {
    try {
        const response = yield call(() => updateReportRequest(action.payload));
        yield put(updateReportSuccess(response));
    } catch (error) {
        yield put(updateReportFailure(error));
    }
}

function* deleteReportFromServer(action) {
    try {
        const response = yield call(() => deleteReportRequest(action.payload));
        yield put(deleteReportSuccess(response));
    } catch (error) {
        yield put(deleteReportFailure(error));
    }
}

function* changeReportForm(action) {
    yield put(onChangeReportFormSuccess(action.payload));
}

function* previewReport(action) {
    const url = window.location;
    window.open(`${url.protocol}//${url.host}/document/preview/Report/${action.payload}`);
    yield put(previewReportSuccess(action.payload));
}


export function* getReport() {
    yield takeLatest(GET_REPORT, getReportFromServer);
}

export function* getReportById() {
    yield takeLatest(GET_REPORT_BY_ID, getReportByIdFromServer);
}

export function* saveReport() {
    yield takeLatest(SAVE_REPORT, saveReportFromServer);
}

export function* updateReport() {
    yield takeLatest(UPDATE_REPORT, updateReportFromServer);
}

export function* deleteReport() {
    yield takeLatest(DELETE_REPORT, deleteReportFromServer);
}

export function* onChangeReportForm() {
    yield takeEvery(ON_CHANGE_REPORT_FORM, changeReportForm);
}

export function* onPreviewReport() {
    yield takeEvery(PREVIEW_REPORT, previewReport);
}


export default function* rootSaga() {
    yield all([
        fork(getReport),
        fork(getReportById),
        fork(saveReport),
        fork(updateReport),
        fork(deleteReport),
        fork(onChangeReportForm),
        fork(onPreviewReport),
    ]);
}