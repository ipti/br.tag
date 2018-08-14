/**
 * Chat App Actions
 */
import {
    CHAT_WITH_SELECTED_USER,
    SEND_MESSAGE_TO_USER,
    UPDATE_USERS_SEARCH,
    SEARCH_USERS,
    GET_RECENT_CHAT_USERS
} from './types';

/**
 * Redux Action To Emit Boxed Layout
 * @param {*boolean} isBoxLayout 
 */
export const chatWithSelectedUser = (user) => ({
    type: CHAT_WITH_SELECTED_USER,
    payload: user
});

export const sendMessageToUser = (data) => ({
    type: SEND_MESSAGE_TO_USER,
    payload: data
});

/**
 * Redux Action To Update User Search
 */
export const updateUsersSearch = (value) => ({
    type: UPDATE_USERS_SEARCH,
    payload: value
});

/**
 * export const to search users
 */
export const onSearchUsers = (value) => ({
    type: SEARCH_USERS,
    payload: value
});

/**
 * Get Recent Chat User
 */
export const getRecentChatUsers = () => ({
    type: GET_RECENT_CHAT_USERS
});
