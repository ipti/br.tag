import  * as actions from 'Actions/FactTypes';
import { EditorState } from 'draft-js';

function setFormFields(data){
    return{
        _id: data._id,
        description: data.description,
        child: data.child,
        providence: data.providence,
    }
}

const INIT_STATE ={
    facts:[],
    fact: {},
    pagination: {
        currentPage: 1,
        perPage: 15,
        totalItens: 0,
        totalPages: 0
    },
    formFields:{
        _id: '',
        description: EditorState.createEmpty(),
        child: '',
        providence: EditorState.createEmpty(),
    },
    loading: false,
    notification: {}
}

export default (state = INIT_STATE, action) => {
    switch (action.type) {
        case actions.GET_FACT:
            return {
                ...state,
                loading: true,
                notification: {}
            }
        
        case actions.GET_FACT_SUCCESS:
            return {
                ...state,
                facts: action.payload.facts,
                pagination: action.payload.pagination,
                loading: false
            }

        case actions.GET_FACT_FAILURE:
            return {
                ...state,
                notification: {type: 'error', message:'Erro ao carregar Registro de Fato'},
                loading: false
            }

        case actions.SAVE_FACT:
            return {
                ...state,
                loading: true,
                notification: {}
            }
        
        case actions.SAVE_FACT_SUCCESS:
            return {
                ...state,
                notification: {type: (action.payload.status === '1' ? 'success': 'error'), message: action.payload.message},
                formFields: (action.payload.status === '1' ? INIT_STATE.formFields : state.formFields),
                loading: false
            }

        case actions.SAVE_FACT_FAILURE:
            return {
                ...state,
                notification: {type: 'error', message:'Erro ao salvar Registro de Fato'},
                loading: false
            }
        
        case actions.ON_CHANGE_FACT_FORM:
            return state

        case actions.ON_CHANGE_FACT_FORM_SUCCESS:
            return {
                ...state,
                formFields: {
                    ...state.formFields,
                    ...action.payload
                },
                notification: {}
            }
        
        case actions.GET_FACT_BY_ID:
            return {
                ...state,
                loading: true,
                fact: {}
            }
        
        case actions.GET_FACT_BY_ID_SUCCESS:
            return {
                ...state,
                fact: action.payload,
                formFields: setFormFields(action.payload),
                notification: {},
                loading: false
            }

        case actions.GET_FACT_BY_ID_FAILURE:
            return {
                ...state,
                fact: {type: 'error', message:'Erro ao carregar Registro de Fato'},
                loading: false
            }
        
        case actions.UPDATE_FACT:
            return {
                ...state,
                loading: true,
                notification: {}
            }
        
        case actions.UPDATE_FACT_SUCCESS:
            return {
                ...state,
                notification: {type: (action.payload.status === '1' ? 'success': 'error'), message: action.payload.message},
                formFields: INIT_STATE.formFields,
                loading: false
            }

        case actions.UPDATE_FACT_FAILURE:
            return {
                ...state,
                notification: {type: 'error', message:'Erro ao salvar Registro de Fato'},
                loading: false
            }

        case actions.DELETE_FACT:
            return {
                ...state,
                loading: true,
                notification: {}
            }
        
        case actions.DELETE_FACT_SUCCESS:
            return {
                ...state,
                notification: {type: (action.payload.status === '1' ? 'success': 'error'), message: action.payload.message},
                loading: false
            }

        case actions.DELETE_FACT_FAILURE:
            return {
                ...state,
                notification: {type: 'error', message:'Erro ao excluir Registro de Fato'},
                loading: false
            }
        case actions.PREVIEW_FACT:
        case actions.PREVIEW_FACT_SUCCESS:
            return state
        
        default:
        return state
    }
}
