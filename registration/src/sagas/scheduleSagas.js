import { put, call, takeLatest } from "redux-saga/effects";
import {
  getSchedules,
  getSchedule,
  getUpdateSchedule,
  getSaveSchedule,
  getError
} from "../actions/scheduleActions";
import api from "../services/api";

// Requests
const requestSchedules = data => {
  let path = data ? "/schedule?page=" + data : "/schedule";
  return api
    .get(path)
    .then(response => response.data)
    .catch(err => {
      throw err;
    });
};

const requestSchedule = id => {
  return api
    .get("/schedule/" + id)
    .then(response => response.data)
    .catch(err => {
      throw err;
    });
};

const requestSaveSchedule = data => {
  return api
    .post("/schedule", data)
    .then(response => response.data)
    .catch(err => {
      throw err;
    });
};

const requestUpdateSchedule = (data, id) => {
  return api
    .put("/schedule/" + id, data)
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
    const result = yield call(requestSchedules, action.page);
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

function* fetchSaveSchedule(action) {
  try {
    const result = yield call(requestSaveSchedule, action.data);
    yield put(getSaveSchedule(result));
  } catch (e) {
    yield put(getError());
  }
}

function* fetchUpdateSchedule(action) {
  try {
    const result = yield call(requestUpdateSchedule, action.data, action.id);
    yield put(getUpdateSchedule(result));
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

export function* watchFetchSaveSchedule() {
  yield takeLatest("FETCH_SAVE_SCHEDULE", fetchSaveSchedule);
}

export function* watchFetchUpdateSchedule() {
  yield takeLatest("FETCH_UPDATE_SCHEDULE", fetchUpdateSchedule);
}
