/**
 * Feedback Sagas
 */
import { all, call, fork, put, takeLatest, takeEvery } from 'redux-saga/effects';

// api
import Api from 'Api';

import {
    GET_FOOD,
    GET_FOOD_BY_ID,
    SAVE_FOOD,
    UPDATE_FOOD,
    DELETE_FOOD,
    PREVIEW_FOOD,
    ON_CHANGE_FOOD_FORM
} from 'Actions/FoodTypes';

import {
    getFoodSuccess,
    getFoodFailure,
    getFoodByIdSuccess,
    getFoodByIdFailure,
    saveFoodSuccess,
    saveFoodFailure,
    updateFoodSuccess,
    updateFoodFailure,
    deleteFoodSuccess,
    deleteFoodFailure,
    onChangeFoodFormSuccess,
    previewFoodSuccess
} from 'Actions';


const getFoodRequest = async (page) =>
    await Api.get(`/v1/food?page=${page}`)
        .then(response => response)
        .catch(error => error);

const getFoodByIdRequest = async (id) =>
    await Api.get(`/v1/food/get/${id}`)
        .then(response => response)
        .catch(error => error);

const saveFoodRequest = async (data) =>
    await Api.post('/v1/food', data)
        .then(response => response)
        .catch(error => error);

const updateFoodRequest = async (data) =>
    await Api.post(`/v1/food/${data._id}`, data)
        .then(response => response)
        .catch(error => error);

const deleteFoodRequest = async (id) =>
    await Api.delete(`/v1/food/${id}`)
        .then(response => response)
        .catch(error => error);


function* getFoodFromServer(action) {
    try {
        const response = yield call(() => getFoodRequest(action.payload));
        yield put(getFoodSuccess(response));
    } catch (error) {
        yield put(getFoodFailure(error));
    }
}

function* getFoodByIdFromServer(action) {
    try {
        const response = yield call(() => getFoodByIdRequest(action.payload));
        yield put(getFoodByIdSuccess(response));
    } catch (error) {
        yield put(getFoodByIdFailure(error));
    }
}

function* saveFoodFromServer(action) {
    try {
        const response = yield call(() => saveFoodRequest(action.payload));
        yield put(saveFoodSuccess(response));
    } catch (error) {
        yield put(saveFoodFailure(error));
    }
}

function* updateFoodFromServer(action) {
    try {
        const response = yield call(() => updateFoodRequest(action.payload));
        yield put(updateFoodSuccess(response));
    } catch (error) {
        yield put(updateFoodFailure(error));
    }
}

function* deleteFoodFromServer(action) {
    try {
        const response = yield call(() => deleteFoodRequest(action.payload));
        yield put(deleteFoodSuccess(response));
    } catch (error) {
        yield put(deleteFoodFailure(error));
    }
}

function* changeFoodForm(action) {
    yield put(onChangeFoodFormSuccess(action.payload));
}

function* previewFood(action) {
    const url = window.location;
    window.open(`${url.protocol}//${url.host}/document/preview/food/${action.payload}`);
    yield put(previewFoodSuccess(action.payload));
}


export function* getFood() {
    yield takeLatest(GET_FOOD, getFoodFromServer);
}

export function* getFoodById() {
    yield takeLatest(GET_FOOD_BY_ID, getFoodByIdFromServer);
}

export function* saveFood() {
    yield takeLatest(SAVE_FOOD, saveFoodFromServer);
}

export function* updateFood() {
    yield takeLatest(UPDATE_FOOD, updateFoodFromServer);
}

export function* deleteFood() {
    yield takeLatest(DELETE_FOOD, deleteFoodFromServer);
}

export function* onChangeFoodForm() {
    yield takeEvery(ON_CHANGE_FOOD_FORM, changeFoodForm);
}

export function* onPreviewFood() {
    yield takeEvery(PREVIEW_FOOD, previewFood);
}


export default function* rootSaga() {
    yield all([
        fork(getFood),
        fork(getFoodById),
        fork(saveFood),
        fork(updateFood),
        fork(deleteFood),
        fork(onChangeFoodForm),
        fork(onPreviewFood),
    ]);
}