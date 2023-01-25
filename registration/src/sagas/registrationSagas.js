import { put, call, takeLatest } from "redux-saga/effects";
import * as actions from "../actions/registrationTypes";
import {
  getStudent,
  getRegistration,
  getError,
  getPeriodRegistration,
  getSchoolList
} from "../actions/registrationActions";
import api from "../services/api";

// Requests
const requestStudent = id => {
  return api
    .get("/student-pre-identify/studentidentification/" + id,
      {
        params: {
          include: {
            edcenso_city: true,
          }
        }
      })
    .then(response => response.data)
    .catch(err => {
      throw err;
    });
};

const parseBool = value =>
  ['true', 'false'].includes(value) ? value === true : null

const requestSaveRegistration = data => {

  console.log(data)
  return api
    .post("/student-pre-identification", data)
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

const requestSchoolList = id => {
  return api
    .get("/student-pre-identify/school", {
      params: {
        include: {
          classroom: true,
          calendar_event: true
        }
      }
    })
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

function* fetchSchoolList(action) {
  try {
    const result = yield call(requestSchoolList, action);
    yield put(getSchoolList(result));
  } catch (e) {
    yield put(getError(e.message));
  }
}

// Watchs
export function* watchFetchStudent() {
  yield takeLatest(actions.FETCH_STUDENT, fetchStudent);
}

export function* watchFetchSaveRegistration() {
  yield takeLatest(actions.FETCH_SAVE_REGISTRATION, fetchSaveRegistration);
}

export function* watchFetchPeriodRegistration() {
  yield takeLatest(actions.FETCH_PERIOD, fetchPeriodRegistration);
}

export function* watchFetchSchoolsList() {
  yield takeLatest(actions.FETCH_SCHOOLS_LIST, fetchSchoolList);
}
