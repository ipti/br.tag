import { put, call, takeLatest } from "redux-saga/effects";
import * as actions from "../actions/classroomTypes";
import {
  getClassrooms,
  getClassroom,
  getRegistration,
  getUpdateRegistration,
  getError,
  getUpdateClassroom
} from "../actions/classroomActions";
import api from "../services/api";

// Requests
const requestClassrooms = data => {
  let path = "/classroom";
  return api
    .get(path)
    
    .then(response => response.data)
    .catch(err => {
      throw err;
    });
};

const requestClassroom = id => {
  return api
    .get("/classroom/" + id)
    .then(response => response.data)
    .catch(err => {
      throw err;
    });
};

const requestRegistration = id => {
  return api
    .get("/" + id)
    .then(response => response.data)
    .catch(err => {
      throw err;
    });
};

const requestSaveClassroom = data => {
  return api
    .post("/classroom", data)
    .then(response => response.data)
    .catch(err => {
      throw err;
    });
};

const requestUpdateRegistration = (data, id) => {
  return api
    .put("/" + id, data)
    .then(response => response.data)
    .catch(err => {
      throw err;
    });
};

const requestUpdateClassroom = (data, id) => {
  return api
    .put("/classroom/" + id, data)
    .then(response => response.data)
    .catch(err => {
      throw err;
    });
};

// Actions
function* fetchClassrooms(action) {
  try {
    const result = yield call(requestClassrooms);
    yield put(getClassrooms(result));
  } catch (e) {
    yield put(getError(e.message));
  }
}

function* fetchClassroomsPage(action) {
  try {
    const result = yield call(requestClassrooms, action.page);
    yield put(getClassrooms(result));
  } catch (e) {
    yield put(getError(e.message));
  }
}

function* fetchClassroom(action) {
  try {
    const result = yield call(requestClassroom, action.data.id);
    yield put(getClassroom(result));
  } catch (e) {
    yield put(getError(e.message));
  }
}

function* fetchRegistration(action) {
  try {
    const result = yield call(requestRegistration, action.data.id);
    yield put(getRegistration(result));
  } catch (e) {
    yield put(getError(e.message));
  }
}

function* fetchSaveClassroom(action) {
  try {
    const result = yield call(requestSaveClassroom, action.data);
    yield put(getClassroom(result));
  } catch (e) {
    yield put(getError(e.message));
  }
}

function* fetchUpdateRegistration(action) {
  try {
    const result = yield call(
      requestUpdateRegistration,
      action.data,
      action.id
    );
    yield put(getUpdateRegistration(result));
  } catch (e) {
    yield put(getError(e.message));
  }
}

function* fetchUpdateClassroom(action) {
  try {
    const result = yield call(requestUpdateClassroom, action.data, action.id);
    yield put(getUpdateClassroom(result));
  } catch (e) {
    yield put(getError(e.message));
  }
}

// Watchs
export function* watchFetchClassrooms() {
  yield takeLatest(actions.FETCH_CLASSROOMS, fetchClassrooms);
}

export function* watchFetchRegistration() {
  yield takeLatest(actions.FETCH_REGISTRATION, fetchRegistration);
}

export function* watchFetchClassroom() {
  yield takeLatest(actions.FETCH_CLASSROOM, fetchClassroom);
}

export function* watchFetchClassroomsPage() {
  yield takeLatest(actions.FETCH_CLASSROOMS_PAGE, fetchClassroomsPage);
}

export function* watchFetchSaveClassroom() {
  yield takeLatest(actions.FETCH_SAVE_CLASSROOM, fetchSaveClassroom);
}

export function* watchFetchUpdateClassroom() {
  yield takeLatest(actions.FETCH_UPDATE_CLASSROOM, fetchUpdateClassroom);
}

export function* watchFetchUpdateRegistration() {
  yield takeLatest(actions.FETCH_UPDATE_REGISTRATION, fetchUpdateRegistration);
}
