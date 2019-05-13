import * as actions from './ReportTypes';

export const getReport = (page = 1) => ({
    type: actions.GET_REPORT,
    payload: page
});

export const getReportSuccess = (response) => ({
    type: actions.GET_REPORT_SUCCESS,
    payload: response.data
});

export const getReportFailure = (error) => ({
    type: actions.GET_REPORT_FAILURE,
    payload: error
});

export const getReportById = (id) => ({
    type: actions.GET_REPORT_BY_ID,
    payload: id
});

export const getReportByIdSuccess = (response) => ({
    type: actions.GET_REPORT_BY_ID_SUCCESS,
    payload: response.data
});

export const getReportByIdFailure = (error) => ({
    type: actions.GET_REPORT_BY_ID_FAILURE,
    payload: error
});

export const onChangeReportForm = (field) => ({
    type: actions.ON_CHANGE_REPORT_FORM,
    payload: field
});

export const onChangeReportFormSuccess = (field) => ({
    type: actions.ON_CHANGE_REPORT_FORM_SUCCESS,
    payload: field
});

export const saveReport = (data) => ({
    type: actions.SAVE_REPORT,
    payload: data
});

export const saveReportSuccess = (response) => ({
    type: actions.SAVE_REPORT_SUCCESS,
    payload: response.data
});

export const saveReportFailure = (error) => ({
    type: actions.SAVE_REPORT_FAILURE,
    payload: error
});

export const updateReport = (data) => ({
    type: actions.UPDATE_REPORT,
    payload: data
});

export const updateReportSuccess = (response) => ({
    type: actions.UPDATE_REPORT_SUCCESS,
    payload: response.data
});

export const updateReportFailure = (error) => ({
    type: actions.DELETE_REPORT_FAILURE,
    payload: error
});

export const deleteReport = (data) => ({
    type: actions.DELETE_REPORT,
    payload: data
});

export const deleteReportSuccess = (response) => ({
    type: actions.DELETE_REPORT_SUCCESS,
    payload: response.data
});

export const deleteReportFailure = (error) => ({
    type: actions.UPDATE_REPORT_FAILURE,
    payload: error
});

export const previewReport = (data) => ({
    type: actions.PREVIEW_REPORT,
    payload: data
});

export const previewReportSuccess = (data) => ({
    type: actions.PREVIEW_REPORT_SUCCESS,
    payload: data
});