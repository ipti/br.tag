/**
 * Todo App Actions
 */
import {
    GET_TODOS,
    GET_TODOS_SUCCESS,
    GET_TODOS_FAILURE,
    FETCH_TODOS,
    ADD_NEW_TASK,
    GET_FILTER_TODOS,
    ON_SELECT_TODO,
    ON_HIDE_LOADER,
    ON_BACK_TO_TODOS,
    ON_SHOW_LOADER,
    MARK_AS_STAR_TODO,
    DELETE_TODO,
    ADD_LABELS_INTO_THE_TASK,
    GET_ALL_TODO,
    GET_COMPLETED_TODOS,
    GET_DELETED_TODOS,
    GET_STARRED_TODOS,
    CLOSE_SNACKBAR,
    COMPLETE_TASK,
    UPDATE_TASK_TITLE,
    UPDATE_TASK_DESCRIPTION,
    CHANGE_TASK_ASSIGNER,
    ON_CHECK_BOX_TOGGLE_TODO_ITEM,
    SELECT_ALL_TODO,
    GET_UNSELECTED_ALL_TODO,
    SELECT_STARRED_TODO,
    SELECT_UNSTARRED_TODO,
    ON_LABEL_SELECT,
    ON_LABEL_MENU_ITEM_SELECT,
    UPDATE_SEARCH,
    SEARCH_TODO
} from './types';

/**
 * Redux Action Get Todos
 */
export const getTodos = () => ({
    type: GET_TODOS
});

/**
 * Redux Action Get Todos Success
 */
export const getTodosSuccess = (response) => ({
    type: GET_TODOS_SUCCESS,
    payload: response.data
});

/**
 * Redux Action Get Todos Failure
 */
export const getTodosFailure = (error) => ({
    type: GET_TODOS_FAILURE,
    payload: error
});

/**
 * Redux Action To Fetch To Todos
 */
export const fetchTodos = () => ({
    type: FETCH_TODOS
})

/**
 * Redux Action To Add New Task
 */
export const addNewTaskAction = (newTaskData) => ({
    type: ADD_NEW_TASK,
    payload: newTaskData
});

/**
 * Redux Action To Activate The Filter
 */
export const activateFilterAction = (activeFilterIndex) => ({
    type: GET_FILTER_TODOS,
    payload: activeFilterIndex
});


/**
 * Redux Action On Select Todo 
 */
export const onSelectTodoAction = (todo) => ({
    type: ON_SELECT_TODO,
    payload: todo
});

/**
 * Redux Action To Hide The Loding Indicator
 */
export const hideLoadingIndicatorAction = () => ({
    type: ON_HIDE_LOADER
});

/**
 * Redux Action On Back To Todos
 */
export const backToTodosAction = () => ({
    type: ON_BACK_TO_TODOS
});


/**
 * Redux Action To Show Loader
 */
export const showLoadingIndicatorAction = () => ({
    type: ON_SHOW_LOADER
});

/**
 * Redux Action To Mark As Star Todo
 */
export const markAsStarTodoAction = (task) => ({
    type: MARK_AS_STAR_TODO,
    payload: task
});

/**
 * Redux Action To Delete The Todo
 */
export const deleteTodoAction = (task) => ({
    type: DELETE_TODO
});


/**
 * Redux Action To Add Labels Into The Task
 */
export const addLabelsIntoTheTaskAction = (label) => ({
    type: ADD_LABELS_INTO_THE_TASK,
    payload: label
});

/**
 * Redux Action To Get All Todo
 */
export const getAllTodoAction = () => ({
    type: GET_ALL_TODO
});

/**
 * Redux Action To Get Completed Todos
 */
export const getCompletedTodosAction = () => ({
    type: GET_COMPLETED_TODOS
});

/**
 * Redux Action To Get Deleted Todos
 */
export const getDeletedTodosAction = () => ({
    type: GET_DELETED_TODOS
});

/**
 * Redux Action To Get Starred Todos
 */
export const getStarredTodosAction = () => ({
    type: GET_STARRED_TODOS
});

/**
 * Redux Action To Close Snackbar
 */
export const closeSnakbarAction = () => ({
    type: CLOSE_SNACKBAR
});

/**
 * Redux Action To Complete The Task
 */
export const completeTask = () => ({
    type: COMPLETE_TASK
});

/**
 * Redux Action To Update The Task Title
 */
export const updateTaskTitle = (newTitle) => ({
    type: UPDATE_TASK_TITLE,
    payload: newTitle
});

/**
 * Redux Action To Update Task Description
 */
export const updateTaskDescription = (newTaskDescription) => ({
    type: UPDATE_TASK_DESCRIPTION,
    payload: newTaskDescription
});

/**
 * Redux Action To Change Task Assinger
 */
export const changeTaskAssigner = (user) => ({
    type: CHANGE_TASK_ASSIGNER,
    payload: user
});

/**
 * Redux Action On Toggle Checkbox Todo Item
 */
export const onCheckBoxToggleTodoItem = (todo) => ({
    type: ON_CHECK_BOX_TOGGLE_TODO_ITEM,
    payload: todo
});

/**
 * Select All Todo
 */
export const selectAllTodo = () => ({
    type: SELECT_ALL_TODO
});

/**
 * Gel Unselected Todo
 */
export const getUnselectedAllTodo = () => ({
    type: GET_UNSELECTED_ALL_TODO
});

/**
 * Get Starred Todo
 */
export const selectStarredTodo = () => ({
    type: SELECT_STARRED_TODO
});

/**
 * Select Unstarred Todo
 */
export const selectUnStarredTodo = () => ({
    type: SELECT_UNSTARRED_TODO
});

/**
 * On Label Select
 */
export const onLabelSelect = () => ({
    type: ON_LABEL_SELECT
});

/**
 * On Label Menu Select
 */
export const onLabelMenuItemSelect = (label) => ({
    type: ON_LABEL_MENU_ITEM_SELECT,
    payload: label
});

/**
 * Search Form Hanlder
 */
export const updateSearch = (searchText) => ({
    type: UPDATE_SEARCH,
    payload: searchText
});

/**
 * Redux Action On Search Todo
 */
export const onSearchTodo = (searchText) => ({
    type: SEARCH_TODO,
    payload: searchText
});

