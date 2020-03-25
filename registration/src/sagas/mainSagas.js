import { fork, all } from "redux-saga/effects";
import {
  watchFetchSchools,
  watchFetchSchool,
  watchFetchSchoolsPage
} from "./schoolSagas";

import {
  watchFetchSchedules,
  watchFetchSchedule,
  watchFetchSchedulesPage,
  watchFetchSaveSchedule,
  watchFetchUpdateSchedule
} from "./scheduleSagas";

import {
  watchFetchClassrooms,
  watchFetchClassroom,
  watchFetchClassroomsPage,
  watchFetchSaveClassroom,
  watchFetchRegistration,
  watchFetchUpdateRegistration,
  watchFetchUpdateClassroom
} from "./classroomSagas";

import {
  watchFetchStudent,
  watchFetchSaveRegistration,
  watchFetchPeriodRegistration,
  watchFetchSchoolsList
} from "./registrationSagas";

export default function* rootSagas() {
  yield all([
    fork(watchFetchSchools),
    fork(watchFetchSchool),
    fork(watchFetchSchoolsPage),
    fork(watchFetchSchedules),
    fork(watchFetchSchedule),
    fork(watchFetchSchedulesPage),
    fork(watchFetchSaveSchedule),
    fork(watchFetchUpdateSchedule),
    fork(watchFetchClassrooms),
    fork(watchFetchClassroom),
    fork(watchFetchClassroomsPage),
    fork(watchFetchSaveClassroom),
    fork(watchFetchRegistration),
    fork(watchFetchUpdateRegistration),
    fork(watchFetchUpdateClassroom),
    fork(watchFetchStudent),
    fork(watchFetchSaveRegistration),
    fork(watchFetchPeriodRegistration),
    fork(watchFetchSchoolsList)
  ]);
}
