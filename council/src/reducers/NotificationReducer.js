import  * as actions from 'Actions/NotificationTypes';

function setFormFields(data){
    return{
        _id: data._id,
        notified: data.notified,
        date: data.date,
        time: data.time
    }
}

const INIT_STATE ={
    notifications:[],
    notification: {},
    pagination: {
        currentPage: 1,
        perPage: 15,
        totalItens: 0,
        totalPages: 0
    },
    formFields:{
        _id: '',
        notified: '',
        date: '',
        time: '',
    },
    loading: false,
    _notification: {}
}

export default (state = INIT_STATE, action) => {
    switch (action.type) {
        case actions.GET_NOTIFICATION:
            return {
                ...state,
                loading: true,
                _notification: {}
            }
        
        case actions.GET_NOTIFICATION_SUCCESS:
            return {
                ...state,
                notifications: action.payload.notifications,
                pagination: action.payload.pagination,
                _notification: {type: 'success', message:'Notificações carregadas com sucesso'},
                loading: false
            }

        case actions.GET_NOTIFICATION_FAILURE:
            return {
                ...state,
                _notification: {type: 'error', message:'Erro ao carregar notificações'},
                loading: false
            }

        case actions.SAVE_NOTIFICATION:
            return {
                ...state,
                loading: true,
                _notification: {}
            }
        
        case actions.SAVE_NOTIFICATION_SUCCESS:
            return {
                ...state,
                _notification: {type: (action.payload.status === '1' ? 'success': 'error'), message: action.payload.message},
                formFields: (action.payload.status === '1' ? INIT_STATE.formFields : state.formFields),
                loading: false
            }

        case actions.SAVE_NOTIFICATION_FAILURE:
            return {
                ...state,
                _notification: {type: 'error', message:'Erro ao salvar notificação'},
                loading: false
            }
        
        case actions.ON_CHANGE_NOTIFICATION_FORM:
            return state

        case actions.ON_CHANGE_NOTIFICATION_FORM_SUCCESS:
            return {
                ...state,
                formFields: {
                    ...state.formFields,
                    ...action.payload
                },
                _notification: {}
            }
        
        case actions.GET_NOTIFICATION_BY_ID:
            return {
                ...state,
                loading: true,
                notification: {}
            }
        
        case actions.GET_NOTIFICATION_BY_ID_SUCCESS:
            return {
                ...state,
                notification: action.payload,
                formFields: setFormFields(action.payload),
                _notification: {},
                loading: false
            }

        case actions.GET_NOTIFICATION_BY_ID_FAILURE:
            return {
                ...state,
                notification: {type: 'error', message:'Erro ao carregar notificação'},
                loading: false
            }
        
        case actions.UPDATE_NOTIFICATION:
            return {
                ...state,
                loading: true,
                _notification: {}
            }
        
        case actions.UPDATE_NOTIFICATION_SUCCESS:
            return {
                ...state,
                _notification: {type: (action.payload.status === '1' ? 'success': 'error'), message: action.payload.message},
                formFields: INIT_STATE.formFields,
                loading: false
            }

        case actions.UPDATE_NOTIFICATION_FAILURE:
            return {
                ...state,
                _notification: {type: 'error', message:'Erro ao salvar notificação'},
                loading: false
            }

        case actions.DELETE_NOTIFICATION:
            return {
                ...state,
                loading: true,
                _notification: {}
            }
        
        case actions.DELETE_NOTIFICATION_SUCCESS:
            return {
                ...state,
                _notification: {type: (action.payload.status === '1' ? 'success': 'error'), message: action.payload.message},
                loading: false
            }

        case actions.DELETE_NOTIFICATION_FAILURE:
            return {
                ...state,
                _notification: {type: 'error', message:'Erro ao excluir notificação'},
                loading: false
            }
        case actions.PREVIEW_NOTIFICATION:
        case actions.PREVIEW_NOTIFICATION_SUCCESS:
            return state
        
        default:
        return state
    }
}
