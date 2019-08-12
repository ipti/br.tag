import * as actions from './AttendanceTypes';

export const getAttendance = () => ({
    type: actions.GET_ATTENDANCE,
});

export const getAttendanceSuccess = (response) => ({
    type: actions.GET_ATTENDANCE_SUCCESS,
    payload: response.data
});

export const getAttendanceFailure = (error) => ({
    type: actions.GET_ATTENDANCE_FAILURE,
    payload: error
});

export const getAttendanceType = () => ({
    type: actions.GET_ATTENDANCE_TYPE,
});

export const getAttendanceTypeSuccess = (response) => ({
    type: actions.GET_ATTENDANCE_TYPE_SUCCESS,
    payload: response.data
});

export const getAttendanceTypeFailure = (error) => ({
    type: actions.GET_ATTENDANCE_TYPE_FAILURE,
    payload: error
});

export const onChangeAttendanceForm = (field) => ({
    type: actions.ON_CHANGE_ATTENDANCE_FORM,
    payload: field
});

export const onChangeAttendanceFormSuccess = (field) => ({
    type: actions.ON_CHANGE_ATTENDANCE_FORM_SUCCESS,
    payload: field
});

export const saveAttendance = (data) => ({
    type: actions.SAVE_ATTENDANCE,
    payload: data
});

export const saveAttendanceSuccess = (response) => ({
    type: actions.SAVE_ATTENDANCE_SUCCESS,
    payload: response.data
});

export const saveAttendanceFailure = (error) => ({
    type: actions.SAVE_ATTENDANCE_FAILURE,
    payload: error
});
