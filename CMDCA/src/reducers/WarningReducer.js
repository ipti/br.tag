import  * as actions from 'Actions/WarningTypes';
import { EditorState } from 'draft-js';

function setFormFields(data){
    return{
        _id: data._id,
        personAdolescent: data.personAdolescent,
        personRepresentative: data.personRepresentative,
        reason: data.reason
    }
}

const INIT_STATE ={
    warnings:[],
    warning: {},
    pagination: {
        currentPage: 1,
        perPage: 15,
        totalItens: 0,
        totalPages: 0
    },
    formFields:{
        _id: '',
        personAdolescent: '',
        personRepresentative: '',
        reason: EditorState.createEmpty()
    },
    loading: false,
    notification: {}
}

export default (state = INIT_STATE, action) => {
    switch (action.type) {
        case actions.GET_WARNING:
            return {
                ...state,
                loading: true,
                notification: {}
            }
        
        case actions.GET_WARNING_SUCCESS:
            return {
                ...state,
                warnings: action.payload.warnings,
                pagination: action.payload.pagination,
                loading: false
            }

        case actions.GET_WARNING_FAILURE:
            return {
                ...state,
                notification: {type: 'error', message:'Erro ao carregar advertência'},
                loading: false
            }

        case actions.SAVE_WARNING:
            return {
                ...state,
                loading: true,
                notification: {}
            }
        
        case actions.SAVE_WARNING_SUCCESS:
            return {
                ...state,
                notification: {type: (action.payload.status === '1' ? 'success': 'error'), message: action.payload.message},
                formFields: (action.payload.status === '1' ? INIT_STATE.formFields : state.formFields),
                loading: false
            }

        case actions.SAVE_WARNING_FAILURE:
            return {
                ...state,
                notification: {type: 'error', message:'Erro ao salvar advertência'},
                loading: false
            }
        
        case actions.ON_CHANGE_WARNING_FORM:
            return state

        case actions.ON_CHANGE_WARNING_FORM_SUCCESS:
            return {
                ...state,
                formFields: {
                    ...state.formFields,
                    ...action.payload
                },
                notification: {}
            }
        
        case actions.GET_WARNING_BY_ID:
            return {
                ...state,
                loading: true,
                warning: {}
            }
        
        case actions.GET_WARNING_BY_ID_SUCCESS:
            return {
                ...state,
                warning: action.payload,
                formFields: setFormFields(action.payload),
                notification: {},
                loading: false
            }

        case actions.GET_WARNING_BY_ID_FAILURE:
            return {
                ...state,
                notification: {type: 'error', message:'Erro ao carregar advertência'},
                loading: false
            }
        
        case actions.UPDATE_WARNING:
            return {
                ...state,
                loading: true,
                notification: {}
            }
        
        case actions.UPDATE_WARNING_SUCCESS:
            return {
                ...state,
                notification: {type: (action.payload.status === '1' ? 'success': 'error'), message: action.payload.message},
                formFields: INIT_STATE.formFields,
                loading: false
            }

        case actions.UPDATE_WARNING_FAILURE:
            return {
                ...state,
                notification: {type: 'error', message:'Erro ao salvar advertência'},
                loading: false
            }

        case actions.DELETE_WARNING:
            return {
                ...state,
                loading: true,
                notification: {}
            }
        
        case actions.DELETE_WARNING_WARNING:
            return {
                ...state,
                notification: {type: (action.payload.status === '1' ? 'success': 'error'), message: action.payload.message},
                loading: false
            }

        case actions.DELETE_WARNING_FAILURE:
            return {
                ...state,
                notification: {type: 'error', message:'Erro ao excluir advertência'},
                loading: false
            }
        case actions.PREVIEW_WARNING:
        case actions.PREVIEW_WARNING_SUCCESS:
            return state

        default:
            return state
    }
}
