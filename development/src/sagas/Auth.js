/**
 * Auth Sagas
 */
import { all, call, fork, put, takeEvery } from 'redux-saga/effects';
import {
    auth,
    facebookAuthProvider,
    googleAuthProvider,
    twitterAuthProvider,
    githubAuthProvider
} from '../firebase';
import {
    LOGIN_USER,
    LOGIN_FACEBOOK_USER,
    LOGIN_GOOGLE_USER,
    LOGIN_TWITTER_USER,
    LOGIN_GITHUB_USER,
    LOGOUT_USER,
    SIGNUP_USER
} from 'Actions/types';

import {
    signinUserSuccess,
    signinUserFailure,
    signUpUserInFirebaseSuccess,
    signUpUserInFirebaseFailure,
    logoutUserFromFirebaseSuccess,
    logoutUserFromFirebaseFailure
} from 'Actions';

/**
 * Sigin User With Email and Password Request
 */
const signInUserWithEmailPasswordRequest = async (email, password) =>
    await auth.signInWithEmailAndPassword(email, password)
        .then(authUser => authUser)
        .catch(error => error);

/**
 * Signin User With Facebook Request
 */
const signInUserWithFacebookRequest = async () =>
    await auth.signInWithPopup(facebookAuthProvider)
        .then(authUser => authUser)
        .catch(error => error);

/**
* Signin User With Facebook Request
*/
const signInUserWithGoogleRequest = async () =>
    await auth.signInWithPopup(googleAuthProvider)
        .then(authUser => authUser)
        .catch(error => error);

/**
* Signin User With Twitter Request
*/
const signInUserWithTwitterRequest = async () =>
    await auth.signInWithPopup(twitterAuthProvider)
        .then(authUser => authUser)
        .catch(error => error);

/**
* Signin User With Github Request
*/
const signInUserWithGithubRequest = async () =>
    await auth.signInWithPopup(githubAuthProvider)
        .then(authUser => authUser)
        .catch(error => error);

/**
 * Signout Request
 */
const signOutRequest = async () =>
    await auth.signOut().then(authUser => authUser).catch(error => error);

/**
 * Create User
 */
const createUserWithEmailPasswordRequest = async (email, password) =>
    await auth.createUserWithEmailAndPassword(email, password)
        .then(authUser => authUser)
        .catch(error => error);

/**
 * Signin User With Email & Password
 */
function* signInUserWithEmailPassword({ payload }) {
    const { email, password } = payload.user;
    const { history } = payload;
    try {
        const signInUser = yield call(signInUserWithEmailPasswordRequest, email, password);
        if (signInUser.message) {
            yield put(signinUserFailure(signInUser.message));
        } else {
            localStorage.setItem('user_id', signInUser.uid);
            yield put(signinUserSuccess(signInUser));
            history.push('/');
        }
    } catch (error) {
        yield put(signinUserFailure(error));
    }
}

/**
 * Signin User With Facebook Account
 */
function* signinUserWithFacebookAccount({ payload }) {
    try {
        const signUpUser = yield call(signInUserWithFacebookRequest);
        if (signUpUser.message) {
            yield put(signinUserFailure(signUpUser.message));
        } else {
            localStorage.setItem('user_id', signUpUser.uid);
            yield put(signinUserSuccess(signUpUser));
            payload.push('/')
        }
    } catch (error) {
        yield put(signinUserFailure(error));
    }
}

/**
 * Signin User With Google Account
 */
function* signinUserWithGoogleAccount({ payload }) {
    try {
        const signUpUser = yield call(signInUserWithGoogleRequest);
        if (signUpUser.message) {
            yield put(signinUserFailure(signUpUser.message));
        } else {
            localStorage.setItem('user_id', signUpUser.uid);
            yield put(signinUserSuccess(signUpUser));
            payload.push('/')
        }
    } catch (error) {
        yield put(signinUserFailure(error));
    }
}

/**
 * Signin User With Twitter Account
 */
function* signinUserWithTwitterAccount({ payload }) {
    try {
        const signUpUser = yield call(signInUserWithTwitterRequest);
        if (signUpUser.message) {
            yield put(signinUserFailure(signUpUser.message));
        } else {
            localStorage.setItem('user_id', signUpUser.uid);
            yield put(signinUserSuccess(signUpUser));
            payload.push('/')
        }
    } catch (error) {
        yield put(signinUserFailure(error));
    }
}

/**
 * Signin User With Github Account
 */
function* signinUserWithGithubAccount({ payload }) {
    try {
        const signUpUser = yield call(signInUserWithGithubRequest);
        if (signUpUser.message) {
            yield put(signinUserFailure(signUpUser.message));
        } else {
            localStorage.setItem('user_id', signUpUser.uid);
            yield put(signinUserSuccess(signUpUser));
            payload.push('/')
        }
    } catch (error) {
        yield put(signinUserFailure(error));
    }
}

/**
 * Signout User
 */
function* signOut() {
    try {
        yield call(signOutRequest);
        localStorage.removeItem('user_id');
        yield put(logoutUserFromFirebaseSuccess())
    } catch (error) {
        yield put(logoutUserFromFirebaseFailure());
    }
}

/**
 * Create User In Firebase
 */
function* createUserWithEmailPassword({ payload }) {
    const { email, password } = payload.user;
    const { history } = payload
    try {
        const signUpUser = yield call(createUserWithEmailPasswordRequest, email, password);
        if (signUpUser.message) {
            yield put(signUpUserInFirebaseFailure(signUpUser.message));
        } else {
            localStorage.setItem('user_id', signUpUser.uid);
            yield put(signUpUserInFirebaseSuccess(signUpUser));
            history.push('/')
        }
    } catch (error) {
        yield put(signUpUserInFirebaseFailure(error));
    }
}

/**
 * Signin User In Firebase
 */
export function* signinUserInFirebase() {
    yield takeEvery(LOGIN_USER, signInUserWithEmailPassword);
}

/**
 * Signin User With Facebook
 */
export function* signInWithFacebook() {
    yield takeEvery(LOGIN_FACEBOOK_USER, signinUserWithFacebookAccount);
}

/**
 * Signin User With Google
 */
export function* signInWithGoogle() {
    yield takeEvery(LOGIN_GOOGLE_USER, signinUserWithGoogleAccount);
}

/**
 * Signin User With Twitter
 */
export function* signInWithTwitter() {
    yield takeEvery(LOGIN_TWITTER_USER, signinUserWithTwitterAccount);
}

/**
 * Signin User With Github
 */
export function* signInWithGithub() {
    yield takeEvery(LOGIN_GITHUB_USER, signinUserWithGithubAccount);
}

/**
 * Signout User From Firebase
 */
export function* signOutUser() {
    yield takeEvery(LOGOUT_USER, signOut);
}

/**
 * Create User
 */
export function* createUserAccount() {
    yield takeEvery(SIGNUP_USER, createUserWithEmailPassword);
}

/**
 * Auth Root Saga
 */
export default function* rootSaga() {
    yield all([
        fork(signinUserInFirebase),
        fork(signInWithFacebook),
        fork(signInWithGoogle),
        fork(signInWithTwitter),
        fork(signInWithGithub),
        fork(signOutUser),
        fork(createUserAccount)
    ]);
}