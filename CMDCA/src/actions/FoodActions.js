import * as actions from './FoodTypes';

export const getFood = (page = 1) => ({
    type: actions.GET_FOOD,
    payload: page
});

export const getFoodSuccess = (response) => ({
    type: actions.GET_FOOD_SUCCESS,
    payload: response.data
});

export const getFoodFailure = (error) => ({
    type: actions.GET_FOOD_FAILURE,
    payload: error
});

export const getFoodById = (id) => ({
    type: actions.GET_FOOD_BY_ID,
    payload: id
});

export const getFoodByIdSuccess = (response) => ({
    type: actions.GET_FOOD_BY_ID_SUCCESS,
    payload: response.data
});

export const getFoodByIdFailure = (error) => ({
    type: actions.GET_FOOD_BY_ID_FAILURE,
    payload: error
});

export const onChangeFoodForm = (field) => ({
    type: actions.ON_CHANGE_FOOD_FORM,
    payload: field
});

export const onChangeFoodFormSuccess = (field) => ({
    type: actions.ON_CHANGE_FOOD_FORM_SUCCESS,
    payload: field
});

export const saveFood = (data) => ({
    type: actions.SAVE_FOOD,
    payload: data
});

export const saveFoodSuccess = (response) => ({
    type: actions.SAVE_FOOD_SUCCESS,
    payload: response.data
});

export const saveFoodFailure = (error) => ({
    type: actions.SAVE_FOOD_FAILURE,
    payload: error
});

export const updateFood = (data) => ({
    type: actions.UPDATE_FOOD,
    payload: data
});

export const updateFoodSuccess = (response) => ({
    type: actions.UPDATE_FOOD_SUCCESS,
    payload: response.data
});

export const updateFoodFailure = (error) => ({
    type: actions.DELETE_FOOD_FAILURE,
    payload: error
});

export const deleteFood = (data, callback = null) => ({
    type: actions.DELETE_FOOD,
    payload: data,
    callback: callback
});

export const deleteFoodSuccess = (response) => ({
    type: actions.DELETE_FOOD_SUCCESS,
    payload: response.data
});

export const deleteFoodFailure = (error) => ({
    type: actions.UPDATE_FOOD_FAILURE,
    payload: error
});

export const previewFood = (data) => ({
    type: actions.PREVIEW_FOOD,
    payload: data
});

export const previewFoodSuccess = (data) => ({
    type: actions.PREVIEW_FOOD_SUCCESS,
    payload: data
});