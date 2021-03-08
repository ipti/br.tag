import { put, call, takeLatest } from 'redux-saga/effects';
import { getStudents, getError } from '../actions/FollowActions';
import api from '../services/api';

// Requests
const requestStudents = () => {
  return api
    .get('students')
    .then((response) => response.data)
    .catch((err) => {
      throw err;
    });
};

const requestSearchStudents = (data) => {
  return api
    .post(`students/filter?page=${data.page}`, data)
    .then((response) => response.data)
    .catch((err) => {
      throw err;
    });
};

// Actions
function* fetchStudents() {
  try {
    const result = yield call(requestStudents);
    yield put(getStudents(result));
  } catch (e) {
    yield put(getError(e.message));
  }
}

function* fetchStudentsPage(action) {
  try {
    const result = yield call(requestStudents, action.data.page);
    yield put(getStudents(result));
  } catch (e) {
    yield put(getError(e.message));
  }
}

function* fetchStudentsSearch(action) {
  try {
    const result = yield call(requestSearchStudents, action.data);
    yield put(getStudents(result));
  } catch (e) {
    yield put(getError(e.message));
  }
}

// Watchs
export function* watchFetchStudents() {
  yield takeLatest('FETCH_STUDENTS', fetchStudents);
}

export function* watchFetchStudentsPage() {
  yield takeLatest('FETCH_PAGE_STUDENTS', fetchStudentsPage);
}

export function* watchFetchSeachStudents() {
  yield takeLatest('FETCH_SEARCH_STUDENTS', fetchStudentsSearch);
}
