/**
 * Todo Sagas
 */
import { all, call, fork, put, takeEvery } from 'redux-saga/effects';

// api
import api from 'Api';

import {
    GET_TODOS,
} from 'Actions/types';

import {
    getTodosSuccess,
    getTodosFailure
} from 'Actions';

/**
 * Send Todos Request To Server
 */
const getTodosRequest = async () =>
    await api.get('todosForApp.js')
        .then(response => response)
        .catch(error => error);

/**
 * Get Todos From Server
 */
function* getTodosFromServer() {
    try {
        const response = yield call(getTodosRequest);
        yield put(getTodosSuccess(response));
    } catch (error) {
        yield put(getTodosFailure(error));
    }
}

/**
 * Ger Emails
 */
export function* getTodos() {
    yield takeEvery(GET_TODOS, getTodosFromServer);
}

/**
 * Email Root Saga
 */
export default function* rootSaga() {
    yield all([
        fork(getTodos)
    ]);
}