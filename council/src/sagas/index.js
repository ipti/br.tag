/**
 * Root Sagas
 */
import { all } from 'redux-saga/effects';

// sagas
import authSagas from './Auth';
import emailSagas from './Email';
import todoSagas from './Todo';
import feedbacksSagas from './Feedbacks';
import PeopleSagas from './People';
import NotificationSagas from './Notification';
import ServiceSagas from './Service';
import InstitutionSagas from './Institution';

export default function* rootSaga(getState) {
    yield all([
        authSagas(),
        emailSagas(),
        todoSagas(),
        feedbacksSagas(),
        PeopleSagas(),
        NotificationSagas(),
        ServiceSagas(),
        InstitutionSagas()
    ]);
}