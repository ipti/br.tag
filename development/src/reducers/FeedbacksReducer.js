/**
 * Feedbacks Reducers
 */
import update from 'react-addons-update';
import { NotificationManager } from 'react-notifications';

// action types
import {
    GET_FEEDBACKS,
    GET_FEEDBACKS_SUCCESS,
    ON_CHANGE_FEEDBACK_PAGE_TABS,
    MAKE_FAVORITE_FEEDBACK,
    ON_DELETE_FEEDBACK,
    VIEW_FEEDBACK_DETAILS,
    ADD_NEW_FEEDBACK,
    SHOW_FEEDBACK_LOADING_INDICATOR,
    HIDE_FEEDBACK_LOADING_INDICATOR,
    NAVIGATE_TO_BACK,
    REPLY_FEEDBACK,
    SEND_REPLY,
    UPDATE_SEARCH_IDEA,
    ON_SEARCH_IDEA,
    ON_COMMENT_FEEDBACK,
    GET_FEEDBACKS_FAILURE
} from 'Actions/types';

/**
 * initial state
 */
const INIT_STATE = {
    allFeedbacks: null,
    feedbacks: null,
    selectedTab: 0,
    selectedFeedback: null,
    loading: false,
    totalFeedbacksCount: 0,
    searchIdeaText: ''
};

export default (state = INIT_STATE, action) => {
    switch (action.type) {

        case GET_FEEDBACKS:
            return { ...state, loading: true };

        // get feedbacks
        case GET_FEEDBACKS_SUCCESS:
            return {
                ...state,
                allFeedbacks: action.payload,
                feedbacks: action.payload,
                loading: false,
                totalFeedbacksCount: action.payload.length,
                plannedFeedbacksCount: action.payload.filter(feedback => feedback.planned).length,
                progressFeedbacksCount: action.payload.filter(feedback => feedback.inProgress).length
            };

        // get feedbacks failure
        case GET_FEEDBACKS_FAILURE:
            return {
                ...state,
                loading: false,
                allFeedbacks: null,
                feedbacks: null
            }

        // show loading indicator
        case SHOW_FEEDBACK_LOADING_INDICATOR:
            return { ...state, loading: true };

        // hide loading indicator
        case HIDE_FEEDBACK_LOADING_INDICATOR:
            return { ...state, loading: false };

        // on change feedback tab
        case ON_CHANGE_FEEDBACK_PAGE_TABS:
            if (action.payload === 1) {
                const plannedFeedbacks = state.allFeedbacks.filter(feedback => feedback.planned && !feedback.deleted);
                return { ...state, feedbacks: plannedFeedbacks, selectedTab: action.payload };
            }
            if (action.payload === 2) {
                const progressFeedbacks = state.allFeedbacks.filter(feedback => feedback.inProgress && !feedback.deleted);
                return { ...state, selectedTab: action.payload, feedbacks: progressFeedbacks };
            }
            if (action.payload === 3) {
                return { ...state, selectedTab: action.payload };
            }
            return { ...state, feedbacks: state.allFeedbacks.filter(feedback => !feedback.deleted), selectedTab: 0 };

        // make favorite feedback
        case MAKE_FAVORITE_FEEDBACK:
            for (let i = 0; i < state.allFeedbacks.length; i++) {
                const feedback = state.allFeedbacks[i];
                if (feedback.id === action.payload.id) {
                    feedback.liked = !feedback.liked;
                    return update(state, {
                        allFeedbacks: {
                            [i]: { $set: feedback }
                        },
                        feedbacks: {
                            [i]: { $set: feedback }
                        }
                    });
                }
            }
            return { ...state };

        // on delete feedback
        case ON_DELETE_FEEDBACK:
            NotificationManager.success('Feedback Deleted!');
            let indexOfDeletedFeedback = state.feedbacks.indexOf(action.payload);
            let feedbacks = state.feedbacks;
            feedbacks.splice(indexOfDeletedFeedback, 1);
            return { ...state, feedbacks, loading: false };

        // view feedback details
        case VIEW_FEEDBACK_DETAILS:
            return { ...state, selectedFeedback: action.payload, loading: false };

        // add new feedback
        case ADD_NEW_FEEDBACK:
            NotificationManager.success('New Feedback Added!');
            return update(state, {
                allFeedbacks: {
                    $splice: [[0, 0, action.payload]]
                },
                feedbacks: {
                    $splice: [[0, 0, action.payload]]
                },
                loading: { $set: false }
            });

        // navigate to back
        case NAVIGATE_TO_BACK:
            return { ...state, selectedFeedback: null, loading: false };

        // reply feedback
        case REPLY_FEEDBACK:
            let indexOfFeedback = state.feedbacks.indexOf(action.payload);
            return update(state, {
                feedbacks: {
                    [indexOfFeedback]: {
                        replyBox: { $set: !action.payload.replyBox }
                    }
                }
            });

        // send reply
        case SEND_REPLY:
            NotificationManager.success('Reply Sent Successfully!');
            let indexOfReplyFeedback = state.feedbacks.indexOf(action.payload);
            return update(state, {
                feedbacks: {
                    [indexOfReplyFeedback]: {
                        replyBox: { $set: false }
                    }
                },
                loading: { $set: false }
            });

        // update search
        case UPDATE_SEARCH_IDEA:
            return { ...state, searchIdeaText: action.payload };

        // on search ideas
        case ON_SEARCH_IDEA:
            if (action.payload === '') {
                return { ...state, feedbacks: state.allFeedbacks, loading: false };
            } else {
                const searchFeedbacks = state.allFeedbacks.filter((feedback) =>
                    feedback.idea.toLowerCase().indexOf(action.payload.toLowerCase()) > -1);
                return { ...state, feedbacks: searchFeedbacks, loading: false }
            }

        // on comment
        case ON_COMMENT_FEEDBACK:
            return {
                ...state,
                selectedFeedback: {
                    ...state.selectedFeedback,
                    comments: [...state.selectedFeedback.comments, action.payload]
                }
            }

        default: return { ...state };
    }
}
