import { fork, all } from 'redux-saga/effects';
import { watchFetchStudents, watchFetchStudentsPage, watchFetchSeachStudents } from './Follow';
import { watchAlert, watchCloseAlert } from './MainDisplaySagas';

export default function* rootSagas() {
  yield all([
    fork(watchFetchStudents),
    fork(watchAlert),
    fork(watchCloseAlert),
    fork(watchFetchStudentsPage),
    fork(watchFetchSeachStudents)
  ]);
}
