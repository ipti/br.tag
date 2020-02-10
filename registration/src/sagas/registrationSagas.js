import { put, call, takeLatest } from "redux-saga/effects";
import {
  getStudent,
  getRegistration,
  getError
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

// Watchs
export function* watchFetchStudent() {
  yield takeLatest("FETCH_STUDENT", fetchStudent);
}

export function* watchFetchSaveRegistration() {
  yield takeLatest("FETCH_SAVE_REGISTRATION", fetchSaveRegistration);
}
