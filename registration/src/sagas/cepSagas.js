import { put, call, takeLatest } from "redux-saga/effects";
import * as actions from "../actions/cepTypes";
import { getAddress, getError } from "../actions/cepActions";

// Requests
const requestAddress = cep => {
  return  fetch(`https://viacep.com.br/ws/${cep}/json/`)
  .then(res => res.json()).catch(err => {
    throw err;
  });

 
};

// Actions
function* fetchAddress(action) {
  try {
    const result = yield call(requestAddress, action.data.cep);
    yield put(getAddress(result));
  } catch (e) {
    yield put(getError(e.message));
  }
}

// Watchs
export function* watchFetchAddress() {
  yield takeLatest(actions.GET_ADDRESS, fetchAddress);
}

