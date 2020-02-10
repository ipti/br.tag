import { put, call, takeLatest } from "redux-saga/effects";
import { getSchools, getSchool, getError } from "../actions/schoolActions";
import api from "../services/api";

// Requests
const requestSchools = data => {
  let path = data ? "/school?page=" + data : "/school";

  return api
    .get(path)
    .then(response => response.data)
    .catch(err => {
      throw err;
    });
};

const requestSchool = id => {
  return api
    .get("/school/" + id)
    .then(response => response.data)
    .catch(err => {
      throw err;
    });
};

// Actions
function* fetchSchools(action) {
  try {
    const result = yield call(requestSchools);
    yield put(getSchools(result));
  } catch (e) {
    yield put(getError(e.message));
  }
}

function* fetchSchoolsPage(action) {
  try {
    const result = yield call(requestSchools, action.page);
    yield put(getSchools(result));
  } catch (e) {
    yield put(getError(e.message));
  }
}

function* fetchSchool(action) {
  try {
    const result = yield call(requestSchool, action.data.id);
    yield put(getSchool(result));
  } catch (e) {
    yield put(getError(e.message));
  }
}

// Watchs
export function* watchFetchSchools() {
  yield takeLatest("FETCH_SCHOOLS", fetchSchools);
}

export function* watchFetchSchool() {
  yield takeLatest("FETCH_SCHOOL", fetchSchool);
}

export function* watchFetchSchoolsPage() {
  yield takeLatest("FETCH_SCHOOLS_PAGE", fetchSchoolsPage);
}
