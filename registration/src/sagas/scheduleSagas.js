import { put, call, takeLatest } from "redux-saga/effects";
import {
  getSchedules,
  getSchedule,
  getError
} from "../actions/scheduleActions";
import api from "../services/api";

// Requests
const requestSchedules = () => {
  return api
    .get("schedule")
    .then(response => response.data)
    .catch(err => {
      throw err;
    });
};

const requestSchedulesPage = page => {
  return api
    .get("schedule?page=" + page)
    .then(response => response.data)
    .catch(err => {
      throw err;
    });
};

const requestSchedule = id => {
  return api
    .get("schedule/" + id)
    .then(response => response.data)
    .catch(err => {
      throw err;
    });
};

// Actions
function* fetchSchedules(action) {
  try {
    const result = yield call(requestSchedules);
    yield put(getSchedules(result));
  } catch (e) {
    yield put(getError(e.message));
  }
}

function* fetchSchedulesPage(action) {
  try {
    const result = yield call(requestSchedulesPage, action.page);
    yield put(getSchedules(result));
  } catch (e) {
    yield put(getError(e.message));
  }
}

function* fetchSchedule(action) {
  try {
    const result = yield call(requestSchedule, action.data.id);
    yield put(getSchedule(result));
  } catch (e) {
    yield put(getError(e.message));
  }
}

// Watchs
export function* watchFetchSchedules() {
  yield takeLatest("FETCH_SCHEDULES", fetchSchedules);
}

export function* watchFetchSchedule() {
  yield takeLatest("FETCH_SCHEDULE", fetchSchedule);
}

export function* watchFetchSchedulesPage() {
  yield takeLatest("FETCH_SCHEDULES_PAGE", fetchSchedulesPage);
}
