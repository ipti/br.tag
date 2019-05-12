import  * as actions from 'Actions/ServiceTypes';

function setFormFields(data){
    return{
        _id: data._id,
    }
}

const INIT_STATE ={
    services:[],
    service: {},
    pagination: {
        currentPage: 1,
        perPage: 15,
        totalItens: 0,
        totalPages: 0
    },
    formFields:{
        _id: ''
    },
    loading: false,
    notification: {}
}

export default (state = INIT_STATE, action) => {
    switch (action.type) {
        case actions.GET_SERVICE:
            return {
                ...state,
                loading: true,
                notification: {}
            }
        
        case actions.GET_SERVICE_SUCCESS:
            return {
                ...state,
                services: action.payload.services,
                pagination: action.payload.pagination,
                notification: {type: 'success', message:'Serviços carregadas com sucesso'},
                loading: false
            }

        case actions.GET_SERVICE_FAILURE:
            return {
                ...state,
                notification: {type: 'error', message:'Erro ao carregar notificações'},
                loading: false
            }

        case actions.SAVE_SERVICE:
            return {
                ...state,
                loading: true,
                notification: {}
            }
        
        case actions.SAVE_SERVICE_SUCCESS:
            return {
                ...state,
                notification: {type: (action.payload.status === '1' ? 'success': 'error'), message: action.payload.message},
                formFields: (action.payload.status === '1' ? INIT_STATE.formFields : state.formFields),
                loading: false
            }

        case actions.SAVE_SERVICE_FAILURE:
            return {
                ...state,
                notification: {type: 'error', message:'Erro ao salvar Resquisição de Serviço'},
                loading: false
            }
        
        case actions.ON_CHANGE_SERVICE_FORM:
            return state

        case actions.ON_CHANGE_SERVICE_FORM_SUCCESS:
            return {
                ...state,
                formFields: {
                    ...state.formFields,
                    ...action.payload
                },
                notification: {}
            }
        
        case actions.GET_SERVICE_BY_ID:
            return {
                ...state,
                loading: true,
                notification: {}
            }
        
        case actions.GET_SERVICE_BY_ID_SUCCESS:
            return {
                ...state,
                service: action.payload,
                formFields: setFormFields(action.payload),
                notification: {},
                loading: false
            }

        case actions.GET_SERVICE_BY_ID_FAILURE:
            return {
                ...state,
                notification: {type: 'error', message:'Erro ao carregar requisição de serviço'},
                loading: false
            }
        
        case actions.UPDATE_SERVICE:
            return {
                ...state,
                loading: true,
                notification: {}
            }
        
        case actions.UPDATE_SERVICE_SUCCESS:
            return {
                ...state,
                notification: {type: (action.payload.status === '1' ? 'success': 'error'), message: action.payload.message},
                formFields: INIT_STATE.formFields,
                loading: false
            }

        case actions.UPDATE_SERVICE_FAILURE:
            return {
                ...state,
                notification: {type: 'error', message:'Erro ao salvar requisição de serviço'},
                loading: false
            }

        case actions.DELETE_SERVICE:
            return {
                ...state,
                loading: true,
                notification: {}
            }
        
        case actions.DELETE_SERVICE_SUCCESS:
            return {
                ...state,
                notification: {type: (action.payload.status === '1' ? 'success': 'error'), message: action.payload.message},
                loading: false
            }

        case actions.DELETE_SERVICE_FAILURE:
            return {
                ...state,
                notification: {type: 'error', message:'Erro ao excluir requisição de serviço'},
                loading: false
            }
        case actions.PREVIEW_SERVICE:
        case actions.PREVIEW_SERVICE_SUCCESS:
            return state
        
        default:
        return state
    }
}
