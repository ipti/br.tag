/**
 * Email App Actions
 */
import {
    GET_EMAILS,
    GET_EMAIL_SUCCESS,
    GET_EMAIL_FAILURE,
    SET_EMAIL_AS_STAR,
    READ_EMAIL,
    HIDE_LOADING_INDICATOR,
    FETCH_EMAILS,
    ON_SELECT_EMAIL,
    UPDATE_EMAIL_SEARCH,
    SEARCH_EMAIL,
    ON_DELETE_MAIL,
    ON_BACK_PRESS_NAVIGATE_TO_EMAIL_LISTING,
    GET_SENT_EMAILS,
    GET_INBOX,
    GET_DRAFTS_EMAILS,
    GET_SPAM_EMAILS,
    GET_TRASH_EMAILS,
    ON_EMAIL_MOVE_TO_FOLDER,
    SELECT_ALL_EMAILS,
    UNSELECT_ALL_EMAILS,
    ON_SEND_EMAIL,
    EMAIL_SENT_SUCCESSFULLY,
    FILTER_EMAILS_WITH_LABELS,
    ADD_LABELS_INTO_EMAILS
} from './types';

/**
 * Redux Action Get Emails
 */
export const getEmails = () => ({
    type: GET_EMAILS
});

/**
 * Redux Action Get Emails Success
 */
export const getEmailsSuccess = (response) => ({
    type: GET_EMAIL_SUCCESS,
    payload: response.data
})

/**
 * Redux Action Get Emails Failure
 */
export const getEmailsFailure = (error) => ({
    type: GET_EMAIL_FAILURE,
    payload: error
})

/**
 * Redux Action To Mark As Star Email
 */
export const markAsStarEmail = (email) => ({
    type: SET_EMAIL_AS_STAR,
    payload: email
});

/**
 * Redux Action To Read Email
 */
export const readEmail = (email) => ({
    type: READ_EMAIL,
    payload: email
});

/**
 * Redux Action To Hide Loading Indicator
 */
export const hideLoadingIndicator = () => ({
    type: HIDE_LOADING_INDICATOR
});

/**
 * Redux Action Fetch Emails
 */
export const fetchEmails = () => ({
    type: FETCH_EMAILS
});

/**
 * Redux Action On Select Email
 */
export const onSelectEmail = (email) => ({
    type: ON_SELECT_EMAIL,
    payload: email
});


/**
 * Redux Action To Update Search Email
 */
export const updateSearchEmail = (searchText) => ({
    type: UPDATE_EMAIL_SEARCH,
    payload: searchText
})

/**
 * Redux Action To Search Email
 */
export const searchEmail = (searchText) => ({
    type: SEARCH_EMAIL,
    payload: searchText
});

/**
 * Redux Action To Delete Email
 */
export const onDeleteEmail = () => ({
    type: ON_DELETE_MAIL
});


/**
 * Redux Action On Back Press To Navigate Email Listing
 */
export const onNavigateToEmailListing = () => ({
    type: ON_BACK_PRESS_NAVIGATE_TO_EMAIL_LISTING
});


/**
 * Redux Action To Get Sent Emails
 */
export const getSentEmails = () => ({
    type: GET_SENT_EMAILS
});

/**
 * Redux Action To Get Inbox
 */
export const getInbox = () => ({
    type: GET_INBOX
});

/**
 * Redux Action To Get Drafts
 */
export const getDraftsEmails = () => ({
    type: GET_DRAFTS_EMAILS
});

/**
 * Redux Action To Get Spam Emails
 */
export const getSpamEmails = () => ({
    type: GET_SPAM_EMAILS
})

/**
 * Redux Action To Get Trash Emails
 */
export const getTrashEmails = () => ({
    type: GET_TRASH_EMAILS
})

/**
 * Redux Action To Move The Emails Into The Selected Folder
 */
export const onEmailMoveToFolder = (folderId) => ({
    type: ON_EMAIL_MOVE_TO_FOLDER,
    payload: folderId
});

/**
 * Redux Action On All Email Select
 */
export const selectAllEMails = () => ({
    type: SELECT_ALL_EMAILS
});

/**
 * Redux Action To Un Select Unselected Emails
 */
export const getUnselectedAllEMails = () => ({
    type: UNSELECT_ALL_EMAILS
});

/**
 * Redux Action To Send Email
 */
export const sendEmail = () => ({
    type: ON_SEND_EMAIL
});

/**
 * Redux Action On Email Sent Successfully
 */
export const emailSentSuccessfully = () => ({
    type: EMAIL_SENT_SUCCESSFULLY
});

/**
 * Redux Action To Filter Emails With Labels
 */
export const filterEmails = (label) => ({
    type: FILTER_EMAILS_WITH_LABELS,
    payload: label
});

/**
 * Redux Action To Add Labels Into Email
 */
export const addLabelsIntoEmail = (label) => ({
    type: ADD_LABELS_INTO_EMAILS,
    payload: label
});
