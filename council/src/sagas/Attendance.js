/**
 * Feedback Sagas
 */
import { all, call, fork, put, takeLatest, takeEvery } from 'redux-saga/effects';

// api
import Api from 'Api';

import {
    GET_ATTENDANCE,
    GET_ATTENDANCE_TYPE,
    SAVE_ATTENDANCE,
    ON_CHANGE_ATTENDANCE_FORM
} from 'Actions/AttendanceTypes';

import {
    getAttendanceSuccess,
    getAttendanceFailure,
    getAttendanceTypeSuccess,
    getAttendanceTypeFailure,
    saveAttendanceSuccess,
    saveAttendanceFailure,
    onChangeAttendanceFormSuccess,
} from 'Actions';


const getAttendanceRequest = async () =>{
    try{
        const request = await Api.get(`/v1/attendance`);
        return request;
    }
    catch(e){
        Promise.reject(e);
    }
}

const getAttendanceTypeRequest = async () =>{
    try{
        const request = await Api.get(`/v1/attendance/type`);
        return request;
    }
    catch(e){
        Promise.reject(e);
    }
}

const saveAttendanceRequest = async (data) =>{
    try{
        const request = await Api.post('/v1/attendance', data);
        return request;
    }
    catch(e){
        Promise.reject(e);
    }

}


function* getAttendanceFromServer(action) {
    try {
        const response = yield call(() => getAttendanceRequest(action.payload));
        yield put(getAttendanceSuccess(response));
    } catch (error) {
        yield put(getAttendanceFailure(error));
    }
}

function* getAttendanceTypeFromServer(action) {
    try {
        const response = yield call(() => getAttendanceTypeRequest(action.payload));
        yield put(getAttendanceTypeSuccess(response));
    } catch (error) {
        yield put(getAttendanceTypeFailure(error));
    }
}

function* saveAttendanceFromServer(action) {
    try {
        const response = yield call(() => saveAttendanceRequest(action.payload));
        yield put(saveAttendanceSuccess(response));
    } catch (error) {
        yield put(saveAttendanceFailure(error));
    }
}

function* changeAttendanceForm(action) {
    yield put(onChangeAttendanceFormSuccess(action.payload));
}


export function* getAttendance() {
    yield takeLatest(GET_ATTENDANCE, getAttendanceFromServer);
}

export function* getAttendanceType() {
    yield takeLatest(GET_ATTENDANCE_TYPE, getAttendanceTypeFromServer);
}

export function* saveAttendance() {
    yield takeLatest(SAVE_ATTENDANCE, saveAttendanceFromServer);
}

export function* onChangeAttendanceForm() {
    yield takeEvery(ON_CHANGE_ATTENDANCE_FORM, changeAttendanceForm);
}


export default function* rootSaga() {
    yield all([
        fork(getAttendance),
        fork(getAttendanceType),
        fork(saveAttendance),
        fork(onChangeAttendanceForm),
    ]);
}