import  * as actions from 'Actions/FoodTypes';
import { EditorState } from 'draft-js';

function setFormFields(data){
    return{
        _id: data._id,
        personApplicant: data.personApplicant,
        personRepresentative: data.personRepresentative,
        personRequired: data.personRequired,
        reason: data.reason,
        place: data.place,
    }
}

const INIT_STATE ={
    foods:[],
    food: {},
    pagination: {
        currentPage: 1,
        perPage: 15,
        totalItens: 0,
        totalPages: 0
    },
    formFields:{
        _id: '',
        personApplicant: '',
        personRepresentative: '',
        personRequired: '',
        reason: EditorState.createEmpty(),
        place: '',
    },
    loading: false,
    notification: {}
}

export default (state = INIT_STATE, action) => {
    switch (action.type) {
        case actions.GET_FOOD:
            return {
                ...state,
                loading: true,
                notification: {}
            }
        
        case actions.GET_FOOD_SUCCESS:
            return {
                ...state,
                foods: action.payload.foods,
                pagination: action.payload.pagination,
                loading: false
            }

        case actions.GET_FOOD_FAILURE:
            return {
                ...state,
                notification: {type: 'error', message:'Erro ao carregar ação de alimentos'},
                loading: false
            }

        case actions.SAVE_FOOD:
            return {
                ...state,
                loading: true,
                notification: {}
            }
        
        case actions.SAVE_FOOD_SUCCESS:
            return {
                ...state,
                notification: {type: (action.payload.status === '1' ? 'success': 'error'), message: action.payload.message},
                formFields: (action.payload.status === '1' ? INIT_STATE.formFields : state.formFields),
                loading: false
            }

        case actions.SAVE_FOOD_FAILURE:
            return {
                ...state,
                notification: {type: 'error', message:'Erro ao salvar ação de alimentos'},
                loading: false
            }
        
        case actions.ON_CHANGE_FOOD_FORM:
            return state

        case actions.ON_CHANGE_FOOD_FORM_SUCCESS:
            return {
                ...state,
                formFields: {
                    ...state.formFields,
                    ...action.payload
                },
                notification: {}
            }
        
        case actions.GET_FOOD_BY_ID:
            return {
                ...state,
                loading: true,
                food: {}
            }
        
        case actions.GET_FOOD_BY_ID_SUCCESS:
            return {
                ...state,
                food: action.payload,
                formFields: setFormFields(action.payload),
                notification: {},
                loading: false
            }

        case actions.GET_FOOD_BY_ID_FAILURE:
            return {
                ...state,
                notification: {type: 'error', message:'Erro ao carregar ação de alimentos'},
                loading: false
            }
        
        case actions.UPDATE_FOOD:
            return {
                ...state,
                loading: true,
                notification: {}
            }
        
        case actions.UPDATE_FOOD_SUCCESS:
            return {
                ...state,
                notification: {type: (action.payload.status === '1' ? 'success': 'error'), message: action.payload.message},
                formFields: INIT_STATE.formFields,
                loading: false
            }

        case actions.UPDATE_FOOD_FAILURE:
            return {
                ...state,
                notification: {type: 'error', message:'Erro ao salvar ação de alimentos'},
                loading: false
            }

        case actions.DELETE_FOOD:
            return {
                ...state,
                loading: true,
                notification: {}
            }
        
        case actions.DELETE_FOOD_SUCCESS:
            return {
                ...state,
                notification: {type: (action.payload.status === '1' ? 'success': 'error'), message: action.payload.message},
                loading: false
            }

        case actions.DELETE_FOOD_FAILURE:
            return {
                ...state,
                notification: {type: 'error', message:'Erro ao excluir ação de alimento'},
                loading: false
            }
        case actions.PREVIEW_FOOD:
        case actions.PREVIEW_FOOD_SUCCESS:
            return state

        default:
            return state
    }
}
