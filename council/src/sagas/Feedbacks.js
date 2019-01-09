/**
 * Feedback Sagas
 */
import { all, call, fork, put, takeEvery } from 'redux-saga/effects';

// api
import api from 'Api';

import {
    GET_FEEDBACKS,
} from 'Actions/types';

import {
    getFeedbacksSuccess,
    getFeedbacksFailure
} from 'Actions';

/**
 * Send Todos Request To Server
 */
const getFeedbacksRequest = async () =>
    await api.get('feedbacks.js')
        .then(response => response)
        .catch(error => error);

/**
 * Get Todos From Server
 */
function* getFeedbacksFromServer() {
    try {
        const response = yield call(getFeedbacksRequest);
        yield put(getFeedbacksSuccess(response));
    } catch (error) {
        yield put(getFeedbacksFailure(error));
    }
}

/**
 * Ger Emails
 */
export function* getFeedbacks() {
    yield takeEvery(GET_FEEDBACKS, getFeedbacksFromServer);
}

/**
 * Email Root Saga
 */
export default function* rootSaga() {
    yield all([
        fork(getFeedbacks)
    ]);
}