/**
 * Feedback Sagas
 */
import { all, call, fork, put, takeLatest, takeEvery } from 'redux-saga/effects';

// api
import Api from 'Api';

import {
    GET_HOUSING,
    GET_HOUSING_BY_ID,
    SAVE_HOUSING,
    UPDATE_HOUSING,
    DELETE_HOUSING,
    PREVIEW_HOUSING,
    ON_CHANGE_HOUSING_FORM
} from 'Actions/HousingTypes';

import {
    getHousingSuccess,
    getHousingFailure,
    getHousingByIdSuccess,
    getHousingByIdFailure,
    saveHousingSuccess,
    saveHousingFailure,
    updateHousingSuccess,
    updateHousingFailure,
    deleteHousingSuccess,
    deleteHousingFailure,
    onChangeHousingFormSuccess,
    previewHousingSuccess
} from 'Actions';


const getHousingRequest = async (page) =>{
    try{
        const request = await Api.get(`/v1/housing?page=${page}`);
        return request;
    }
    catch(e){
        Promise.reject(e);
    }
}

const getHousingByIdRequest = async (id) => {
    try{
        const request = await Api.get(`/v1/housing/get/${id}`);
        return request;
    }
    catch(e){
        Promise.reject(e);
    }
}

const saveHousingRequest = async (data) =>{
    try{
        const request = await Api.post('/v1/housing', data);
        return request;
    }
    catch(e){
        Promise.reject(e);
    }

}

const updateHousingRequest = async (data) =>{
    try{
        const request = await Api.post(`/v1/housing/${data._id}`, data);
        return request;
    }
    catch(e){
        Promise.reject(e);
    }
}


const deleteHousingRequest = async (id) => {
    try{
        const request = await Api.delete(`/v1/housing/${id}`);
        return request;
    }
    catch(e){
        Promise.reject(e);
    }
}


function* getHousingFromServer(action) {
    try {
        const response = yield call(() => getHousingRequest(action.payload));
        yield put(getHousingSuccess(response));
    } catch (error) {
        yield put(getHousingFailure(error));
    }
}

function* getHousingByIdFromServer(action) {
    try {
        const response = yield call(() => getHousingByIdRequest(action.payload));
        yield put(getHousingByIdSuccess(response));
    } catch (error) {
        yield put(getHousingByIdFailure(error));
    }
}

function* saveHousingFromServer(action) {
    try {
        const response = yield call(() => saveHousingRequest(action.payload));
        yield put(saveHousingSuccess(response));
    } catch (error) {
        yield put(saveHousingFailure(error));
    }
}

function* updateHousingFromServer(action) {
    try {
        const response = yield call(() => updateHousingRequest(action.payload));
        yield put(updateHousingSuccess(response));
    } catch (error) {
        yield put(updateHousingFailure(error));
    }
}

function* deleteHousingFromServer(action) {
    try {
        const response = yield call(() => deleteHousingRequest(action.payload));
        yield put(deleteHousingSuccess(response));
        if (typeof(action.callback) === 'function') {
            action.callback();
        }
    } catch (error) {
        yield put(deleteHousingFailure(error));
    }
}

function* changeHousingForm(action) {
    yield put(onChangeHousingFormSuccess(action.payload));
}

function* previewHousing(action) {
    const url = window.location;
    window.open(`${url.protocol}//${url.host}/document/preview/housing/${action.payload}`);
    yield put(previewHousingSuccess(action.payload));
}


export function* getHousing() {
    yield takeLatest(GET_HOUSING, getHousingFromServer);
}

export function* getHousingById() {
    yield takeLatest(GET_HOUSING_BY_ID, getHousingByIdFromServer);
}

export function* saveHousing() {
    yield takeLatest(SAVE_HOUSING, saveHousingFromServer);
}

export function* updateHousing() {
    yield takeLatest(UPDATE_HOUSING, updateHousingFromServer);
}

export function* deleteHousing() {
    yield takeLatest(DELETE_HOUSING, deleteHousingFromServer);
}

export function* onChangeHousingForm() {
    yield takeEvery(ON_CHANGE_HOUSING_FORM, changeHousingForm);
}

export function* onPreviewHousing() {
    yield takeEvery(PREVIEW_HOUSING, previewHousing);
}


export default function* rootSaga() {
    yield all([
        fork(getHousing),
        fork(getHousingById),
        fork(saveHousing),
        fork(updateHousing),
        fork(deleteHousing),
        fork(onChangeHousingForm),
        fork(onPreviewHousing),
    ]);
}