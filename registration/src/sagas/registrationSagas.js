import { put, call, takeLatest } from "redux-saga/effects";
import {
  getStudent,
  getRegistration,
  getError,
  getPeriodRegistration
} from "../actions/registrationActions";
import api from "../services/api";

// Requests
const requestStudent = id => {
  return api
    .get("/external/searchstudent/" + id)
    .then(response => response.data)
    .catch(err => {
      throw err;
    });
};

const requestSaveRegistration = data => {
  return api
    .post("/external", data)
    .then(response => response.data)
    .catch(err => {
      throw err;
    });
};

const requestPeriodRegistration = () => {
  return api
    .get("/external/schedule/active")
    .then(response => response.data)
    .catch(err => {
      throw err;
    });
};

// Actions
function* fetchStudent(action) {
  try {
    const result = yield call(requestStudent, action.registration);
    yield put(getStudent(result));
  } catch (e) {
    yield put(getError(e.message));
  }
}

function* fetchSaveRegistration(action) {
  try {
    const result = yield call(requestSaveRegistration, action.data);
    yield put(getRegistration(result));
  } catch (e) {
    yield put(getError(e.message));
  }
}

function* fetchPeriodRegistration(action) {
  try {
    const result = yield call(requestPeriodRegistration);
    yield put(getPeriodRegistration(result));
  } catch (e) {
    yield put(getError(e.message));
  }
}

// Watchs
export function* watchFetchStudent() {
  yield takeLatest("FETCH_STUDENT", fetchStudent);
}

export function* watchFetchSaveRegistration() {
  yield takeLatest("FETCH_SAVE_REGISTRATION", fetchSaveRegistration);
}

export function* watchFetchPeriodRegistration() {
  yield takeLatest("FETCH_PERIOD", fetchPeriodRegistration);
}
