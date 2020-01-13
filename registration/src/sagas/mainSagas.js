import { fork, all } from "redux-saga/effects";
import {
  watchFetchSchools,
  watchFetchSchool,
  watchFetchSchoolsPage
} from "./schoolSagas";

import {
  watchFetchSchedules,
  watchFetchSchedule,
  watchFetchSchedulesPage
} from "./scheduleSagas";

export default function* rootSagas() {
  yield all([
    fork(watchFetchSchools),
    fork(watchFetchSchool),
    fork(watchFetchSchoolsPage),
    fork(watchFetchSchedules),
    fork(watchFetchSchedule),
    fork(watchFetchSchedulesPage)
  ]);
}
