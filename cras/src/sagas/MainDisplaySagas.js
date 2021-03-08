import { put, takeLatest, take } from 'redux-saga/effects';

import { openAlert, closeAlert } from '../actions/MainDisplayActions';

function* alert(action) {
  yield put(openAlert(action.payload));
}

function* closeAlertBox() {
  yield put(closeAlert());
}

export function* watchAlert() {
  yield takeLatest('ALERT', alert);
}

export function* watchCloseAlert() {
  yield take('CLOSE_ALERT', closeAlertBox);
}
