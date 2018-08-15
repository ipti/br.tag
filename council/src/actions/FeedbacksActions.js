/**
 * Feedback Actions
 */
import {
    GET_FEEDBACKS,
    GET_FEEDBACKS_SUCCESS,
    GET_FEEDBACKS_FAILURE,
    GET_ALL_FEEDBACKS,
    ON_CHANGE_FEEDBACK_PAGE_TABS,
    MAKE_FAVORITE_FEEDBACK,
    ON_DELETE_FEEDBACK,
    VIEW_FEEDBACK_DETAILS,
    ADD_NEW_FEEDBACK,
    SHOW_FEEDBACK_LOADING_INDICATOR,
    NAVIGATE_TO_BACK,
    REPLY_FEEDBACK,
    SEND_REPLY,
    UPDATE_SEARCH_IDEA,
    ON_SEARCH_IDEA,
    ON_COMMENT_FEEDBACK
} from './types';

/**
 * Redux Action To Get Feedbacks
 */
export const getFeedbacks = () => ({
    type: GET_FEEDBACKS
});

/**
 * Redux Action To Get Feedbacks Success
 */
export const getFeedbacksSuccess = (response) => ({
    type: GET_FEEDBACKS_SUCCESS,
    payload: response.data
});

/**
 * Redux Action To Get Feedbacks Failure
 */
export const getFeedbacksFailure = (error) => ({
    type: GET_FEEDBACKS_FAILURE,
    payload: error
});

/**
 * Redux Action To Get All Feedbacks
 */
export const getAllFeedbacks = () => ({
    type: GET_ALL_FEEDBACKS
});

/**
 * Redux Action On Change Feedback Page Tabs
 */
export const onChangeFeedbackPageTabs = (value) => ({
    type: ON_CHANGE_FEEDBACK_PAGE_TABS,
    payload: value
});

/**
 * Redux Action Make Favorite Feedback
 */
export const onFeedbackFavorite = (feedback) => ({
    type: MAKE_FAVORITE_FEEDBACK,
    payload: feedback
});

/**
 * Redux Action On Delete Feedback
 */
export const onDeleteFeedback = (feedback) => ({
    type: ON_DELETE_FEEDBACK,
    payload: feedback
});

/**
 * Redux Action To View Feedback Details
 */
export const viewFeedbackDetails = (feedback) => ({
    type: VIEW_FEEDBACK_DETAILS,
    payload: feedback
});

/**
 * Add New Feedback
 */
export const addNewFeedback = (data) => ({
    type: ADD_NEW_FEEDBACK,
    payload: data
});

/**
 * Show Feedback Loading Indicator
 */
export const showFeedbackLoadingIndicator = () => ({
    type: SHOW_FEEDBACK_LOADING_INDICATOR
});

/**
 * Navigate To Back
 */
export const navigateToBack = () => ({
    type: NAVIGATE_TO_BACK
});

/**
 * Redux Action To Reply Feedback
 */
export const replyFeedback = (feedback) => ({
    type: REPLY_FEEDBACK,
    payload: feedback
});

/**
 * Send Reply
 */
export const sendReply = (feedback) => ({
    type: SEND_REPLY,
    payload: feedback
});

/**
 * Update Search Ideas
 */
export const updateSearchIdeas = (value) => ({
    type: UPDATE_SEARCH_IDEA,
    payload: value
});

/**
 * On Search Idea
 */
export const onSearchIdeas = (value) => ({
    type: ON_SEARCH_IDEA,
    payload: value
});

/**
 * On Comment
 */
export const onCommentAction = (data) => ({
    type: ON_COMMENT_FEEDBACK,
    payload: data
});
