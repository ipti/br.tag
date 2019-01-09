/**
 * Todo App Reducer
 */
import update from 'react-addons-update';
// todo data
import users from 'Assets/data/todo-app/users';
import labels from 'Assets/data/todo-app/labels';

// action types
import {
    GET_TODOS,
    GET_TODOS_SUCCESS,
    GET_TODOS_FAILURE,
    FETCH_TODOS,
    ADD_NEW_TASK,
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
    GET_FILTER_TODOS,
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
    ON_LABEL_MENU_ITEM_SELECT,
    UPDATE_SEARCH,
    SEARCH_TODO
} from 'Actions/types';

// initial state
const INIT_STATE = {
    toDos: null,
    users,
    labels,
    activeFilter: 0,
    selectedTodo: null,
    loading: false,
    showMessage: false,
    selectedToDos: 0,
    searchTodo: ''
};

export default (state = INIT_STATE, action) => {
    switch (action.type) {

        // get todos
        case GET_TODOS:
            return { ...state, allToDos: null, toDos: null };

        // get todos success
        case GET_TODOS_SUCCESS:
            return { ...state, allToDos: action.payload, toDos: action.payload };

        // get todos failure
        case GET_TODOS_FAILURE:
            return {}

        // use to fetch un deleted todos
        case FETCH_TODOS:
            let todos = state.allToDos && state.allToDos.filter(todo => {
                if (!todo.deleted) {
                    return todo;
                }
            })
            return {
                ...state,
                toDos: todos
            }

        // add new task
        case ADD_NEW_TASK:
            return {
                ...state,
                allToDos: [...state.allToDos, action.payload],
                showMessage: true,
                message: 'Task Added Successfully',
                toDos: [...state.toDos, action.payload]
            };

        // on select todo
        case ON_SELECT_TODO:
            let indexOfSelectedTodo = state.toDos.indexOf(action.payload);
            let selectedTodo;
            for (let i = 0; i < state.toDos.length; i++) {
                const element = state.toDos[i];
                if (indexOfSelectedTodo === i) {
                    element.selected = true;
                    selectedTodo = element
                }
            }
            return update(state, {
                selectedTodo: { $set: selectedTodo },
                loading: { $set: true }
            });

        // on hide loader
        case ON_HIDE_LOADER:
            return { ...state, loading: false };

        // on show loaded
        case ON_SHOW_LOADER:
            return { ...state, loading: true };

        // on back to todos
        case ON_BACK_TO_TODOS:
            return { ...state, loading: false, selectedTodo: null, toDos: state.allToDos };

        // mark as star todo
        case MARK_AS_STAR_TODO:
            let starredTodo;
            for (let i = 0; i < state.allToDos.length; i++) {
                const todo = state.allToDos[i];
                if (todo.selected) {
                    todo.starred = !todo.starred
                    starredTodo = todo;
                    return update(state, {
                        selectedTodo: {
                            starred: { $set: starredTodo.starred }
                        },
                        allToDos: {
                            [i]: {
                                starred: { $set: starredTodo.starred }
                            }
                        },
                        showMessage: { $set: true },
                        message: { $set: 'Todo Updated!' }
                    })
                }
            }
            break;

        // delete todo
        case DELETE_TODO:
            for (let i = 0; i < state.toDos.length; i++) {
                const todo = state.toDos[i];
                if (todo.selected && !todo.deleted) {
                    todo.deleted = true;
                    return update(state, {
                        selectedTodo: { $set: null },
                        toDos: {
                            [i]: {
                                deleted: { $set: todo.deleted }
                            }
                        },
                        showMessage: { $set: true },
                        message: { $set: 'Todo Deleted Successfully!' }
                    })
                }
            }

        // add labels into the task
        case ADD_LABELS_INTO_THE_TASK:
            for (let i = 0; i < state.toDos.length; i++) {
                const todo = state.toDos[i];
                if (todo.selected) {
                    if (todo.labels.includes(action.payload.value)) {
                        let labelIndex = todo.labels.indexOf(action.payload.value);
                        return update(state, {
                            selectedTodo: {
                                labels: { $splice: [[labelIndex, 1]] }
                            },
                            toDos: {
                                [i]: {
                                    labels: { $splice: [[labelIndex, 1]] }
                                }
                            },
                            showMessage: { $set: true },
                            message: { $set: 'Todo Updated Success!' }
                        });
                    } else {
                        return update(state, {
                            selectedTodo: {
                                labels: { $push: [action.payload.value] }
                            },
                            toDos: {
                                [i]: {
                                    labels: { $push: [action.payload.value] }
                                }
                            },
                            showMessage: { $set: true },
                            message: { $set: 'Todo Updated Success!' }
                        });
                    }
                }
            }

        // get all todos except deleted
        case GET_ALL_TODO:
            // eslint-disable-next-line
            const filterAllTodos = state.allToDos.filter(todo => {
                if (!todo.deleted) {
                    return todo;
                }
            });
            return {
                ...state,
                selectedTodo: null,
                toDos: filterAllTodos
            };

        case GET_COMPLETED_TODOS:
            // eslint-disable-next-line
            const filtersTodos = state.allToDos.filter(todo => {
                if (todo.completed && !todo.deleted) {
                    return todo;
                }
            });
            return { ...state, toDos: filtersTodos, selectedTodo: null };

        // get filters todos with labels
        case GET_FILTER_TODOS:
            const filtersTodoWithLabels = state.allToDos.filter(todo => {
                if (todo.labels.includes(action.payload)) {
                    return todo;
                }
            });
            return { ...state, toDos: filtersTodoWithLabels, selectedTodo: null };

        case GET_DELETED_TODOS:
            // eslint-disable-next-line
            const filtersDeletedTodos = state.allToDos.filter(todo => {
                if (todo.deleted) {
                    return todo;
                }
            });
            return { ...state, toDos: filtersDeletedTodos, selectedTodo: null };

        case GET_STARRED_TODOS:
            // eslint-disable-next-line
            const filtersStarredTodos = state.allToDos.filter(todo => {
                if (todo.starred) {
                    return todo;
                }
            });
            return { ...state, selectedTodo: null, toDos: filtersStarredTodos };

        // close snackbar
        case CLOSE_SNACKBAR:
            return { ...state, showMessage: false };

        // complete task
        case COMPLETE_TASK:
            for (let i = 0; i < state.allToDos.length; i++) {
                const todo = state.allToDos[i];
                if (todo.selected && !todo.deleted) {
                    todo.completed = !todo.completed;
                    return update(state, {
                        selectedTodo: {
                            completed: { $set: todo.completed }
                        },
                        allToDos: {
                            [i]: {
                                completed: { $set: todo.completed }
                            }
                        },
                        showMessage: { $set: true },
                        message: { $set: 'Todo Updated Success!' }
                    })
                }
            }

        // to update task title
        case UPDATE_TASK_TITLE:
            for (let i = 0; i < state.allToDos.length; i++) {
                const todo = state.allToDos[i];
                if (todo.selected) {
                    todo.task_name = action.payload;
                    return update(state, {
                        selectedTodo: {
                            task_name: { $set: todo.task_name }
                        },
                        allToDos: {
                            [i]: {
                                task_name: { $set: todo.task_name }
                            }
                        },
                        showMessage: { $set: true },
                        message: { $set: 'Todo Updated Success!' }
                    })
                }
            }

        // update task description
        case UPDATE_TASK_DESCRIPTION:
            for (let i = 0; i < state.allToDos.length; i++) {
                const todo = state.allToDos[i];
                if (todo.selected) {
                    todo.task_description = action.payload;
                    return update(state, {
                        selectedTodo: {
                            task_description: { $set: todo.task_description }
                        },
                        allToDos: {
                            [i]: {
                                task_description: { $set: todo.task_description }
                            }
                        },
                        showMessage: { $set: true },
                        message: { $set: 'Todo Updated Success!' }
                    })
                }
            }

        // change task assigner
        case CHANGE_TASK_ASSIGNER:
            for (let i = 0; i < state.allToDos.length; i++) {
                const todo = state.allToDos[i];
                if (todo.selected) {
                    todo.assignTo = action.payload;
                    return update(state, {
                        selectedTodo: {
                            assignTo: { $set: todo.assignTo }
                        },
                        allToDos: {
                            [i]: {
                                assignTo: { $set: todo.assignTo }
                            }
                        },
                        showMessage: { $set: true },
                        message: { $set: 'Todo Updated Success!' }
                    })
                }
            }

        // on select checkbox
        case ON_CHECK_BOX_TOGGLE_TODO_ITEM:
            action.payload.task_status = !action.payload.task_status;
            let selectedToDos = 0;
            const toDos = state.toDos.map(todo => {
                if (todo.task_status) {
                    selectedToDos++;
                }
                if (todo.id === action.payload.id) {
                    if (todo.task_status) {
                        selectedToDos++;
                    }
                    return action.payload;
                } else {
                    return todo;
                }
            }
            );
            return {
                ...state,
                selectedToDos: selectedToDos,
                toDos: toDos
            }

        // select all todos
        case SELECT_ALL_TODO:
            let newToDos = state.toDos.map((todo) => todo ? {
                ...todo,
                task_status: true
            } : todo);
            return {
                ...state,
                selectedToDos: newToDos.length,
                toDos: newToDos
            }

        // get unselected all todo
        case GET_UNSELECTED_ALL_TODO:
            let unselectedToDos = state.toDos.map((todo) => todo ? {
                ...todo,
                task_status: false
            } : todo);
            return {
                ...state,
                selectedToDos: 0,
                toDos: unselectedToDos
            }

        // selected starred todo
        case SELECT_STARRED_TODO:
            let selectedStarrdToDos = 0;
            let starredToDos = state.toDos.map((todo) => {
                if (todo.starred) {
                    selectedStarrdToDos++;
                    return { ...todo, task_status: true };
                }
                return { ...todo, task_status: false }
            });
            return {
                ...state,
                selectedToDos: selectedStarrdToDos,
                toDos: starredToDos.filter(todo => !todo.deleted)
            }

        // select unstarred todo
        case SELECT_UNSTARRED_TODO: {
            let selectedUnstarredToDos = 0;
            let unStarredToDos = state.toDos.map((todo) => {
                if (!todo.starred) {
                    selectedUnstarredToDos++;
                    return { ...todo, task_status: true };
                }
                return { ...todo, task_status: false }
            });
            return {
                ...state,
                selectedToDos: selectedUnstarredToDos,
                toDos: unStarredToDos.filter(todo => !todo.deleted)
            }
        }

        // on label menu select
        case ON_LABEL_MENU_ITEM_SELECT:
            const labelsMenutoDos = state.toDos.map(todo => {
                if (todo.task_status) {
                    if (todo.labels.includes(action.payload.value)) {
                        todo.labels.splice(todo.labels.indexOf(action.payload.value), 1);
                        return { ...todo, labels: todo.labels };
                    } else {
                        return { ...todo, labels: todo.labels.concat(action.payload.value) };
                    }
                } else {
                    return todo;
                }
            }
            );
            return {
                ...state,
                message: 'Label Updated Successfully!',
                showMessage: true,
                allToDos: labelsMenutoDos,
                toDos: labelsMenutoDos
            }

        // update search
        case UPDATE_SEARCH:
            return { ...state, searchTodo: action.payload }

        // search todos
        case SEARCH_TODO:
            if (action.payload === '') {
                return { ...state, toDos: state.allToDos.filter((todo) => !todo.deleted) };
            } else {
                const searchToDos = state.allToDos.filter((todo) =>
                    !todo.deleted && todo.task_name.toLowerCase().indexOf(action.payload.toLowerCase()) > -1);
                return { ...state, toDos: searchToDos }
            }

        default: return { ...state };
    }
}
