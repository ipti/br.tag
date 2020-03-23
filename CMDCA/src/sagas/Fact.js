/**
 * Feedback Sagas
 */
import { all, call, fork, put, takeLatest, takeEvery } from 'redux-saga/effects';

// api
import Api from 'Api';

import {
    GET_FACT,
    GET_FACT_BY_ID,
    SAVE_FACT,
    UPDATE_FACT,
    DELETE_FACT,
    PREVIEW_FACT,
    ON_CHANGE_FACT_FORM
} from 'Actions/FactTypes';

import {
    getFactSuccess,
    getFactFailure,
    getFactByIdSuccess,
    getFactByIdFailure,
    saveFactSuccess,
    saveFactFailure,
    updateFactSuccess,
    updateFactFailure,
    deleteFactSuccess,
    deleteFactFailure,
    onChangeFactFormSuccess,
    previewFactSuccess
} from 'Actions';


const getFactRequest = async (page) =>{
    try{
        const request = await Api.get(`/v1/fact?page=${page}`);
        return request;
    }
    catch(e){
        Promise.reject(e);
    }
}

const getFactByIdRequest = async (id) => {
    try{
        const request = await Api.get(`/v1/fact/get/${id}`);
        return request;
    }
    catch(e){
        Promise.reject(e);
    }
}

const saveFactRequest = async (data) =>{
    try{
        const request = await Api.post('/v1/fact', data);
        return request;
    }
    catch(e){
        Promise.reject(e);
    }

}

const updateFactRequest = async (data) =>{
    try{
        const request = await Api.post(`/v1/fact/${data._id}`, data);
        return request;
    }
    catch(e){
        Promise.reject(e);
    }
}


const deleteFactRequest = async (id) => {
    try{
        const request = await Api.delete(`/v1/fact/${id}`);
        return request;
    }
    catch(e){
        Promise.reject(e);
    }
}


function* getFactFromServer(action) {
    try {
        const response = yield call(() => getFactRequest(action.payload));
        yield put(getFactSuccess(response));
    } catch (error) {
        yield put(getFactFailure(error));
    }
}

function* getFactByIdFromServer(action) {
    try {
        const response = yield call(() => getFactByIdRequest(action.payload));
        yield put(getFactByIdSuccess(response));
    } catch (error) {
        yield put(getFactByIdFailure(error));
    }
}

function* saveFactFromServer(action) {
    try {
        const response = yield call(() => saveFactRequest(action.payload));
        yield put(saveFactSuccess(response));
    } catch (error) {
        yield put(saveFactFailure(error));
    }
}

function* updateFactFromServer(action) {
    try {
        const response = yield call(() => updateFactRequest(action.payload));
        yield put(updateFactSuccess(response));
    } catch (error) {
        yield put(updateFactFailure(error));
    }
}

function* deleteFactFromServer(action) {
    try {
        const response = yield call(() => deleteFactRequest(action.payload));
        yield put(deleteFactSuccess(response));
        if (typeof(action.callback) === 'function') {
            action.callback();
        }
    } catch (error) {
        yield put(deleteFactFailure(error));
    }
}

function* changeFactForm(action) {
    yield put(onChangeFactFormSuccess(action.payload));
}

function* previewFact(action) {
    const url = window.location;
    window.open(`${url.protocol}//${url.host}/document/preview/fact/${action.payload}`);
    yield put(previewFactSuccess(action.payload));
}


export function* getFact() {
    yield takeLatest(GET_FACT, getFactFromServer);
}

export function* getFactById() {
    yield takeLatest(GET_FACT_BY_ID, getFactByIdFromServer);
}

export function* saveFact() {
    yield takeLatest(SAVE_FACT, saveFactFromServer);
}

export function* updateFact() {
    yield takeLatest(UPDATE_FACT, updateFactFromServer);
}

export function* deleteFact() {
    yield takeLatest(DELETE_FACT, deleteFactFromServer);
}

export function* onChangeFactForm() {
    yield takeEvery(ON_CHANGE_FACT_FORM, changeFactForm);
}

export function* onPreviewFact() {
    yield takeEvery(PREVIEW_FACT, previewFact);
}


export default function* rootSaga() {
    yield all([
        fork(getFact),
        fork(getFactById),
        fork(saveFact),
        fork(updateFact),
        fork(deleteFact),
        fork(onChangeFactForm),
        fork(onPreviewFact),
    ]);
}