import  * as actions from 'Actions/ReportTypes';

function setFormFields(data){
    return{
        description: data.description,
    }
}

const INIT_STATE ={
    reports:[],
    report: {},
    pagination: {
        currentPage: 1,
        perPage: 15,
        totalItens: 0,
        totalPages: 0
    },
    formFields:{
        description: ''
    },
    loading: false,
    notification: {}
}

export default (state = INIT_STATE, action) => {
    switch (action.type) {
        case actions.GET_REPORT:
            return {
                ...state,
                loading: true,
                notification: {}
            }
        
        case actions.GET_REPORT_SUCCESS:
            return {
                ...state,
                reports: action.payload.reports,
                pagination: action.payload.pagination,
                notification: {type: 'success', message:'Serviços carregadas com sucesso'},
                loading: false
            }

        case actions.GET_REPORT_FAILURE:
            return {
                ...state,
                notification: {type: 'error', message:'Erro ao carregar notificações'},
                loading: false
            }

        case actions.SAVE_REPORT:
            return {
                ...state,
                loading: true,
                notification: {}
            }
        
        case actions.SAVE_REPORT_SUCCESS:
            return {
                ...state,
                notification: {type: (action.payload.status === '1' ? 'success': 'error'), message: action.payload.message},
                formFields: (action.payload.status === '1' ? INIT_STATE.formFields : state.formFields),
                loading: false
            }

        case actions.SAVE_REPORT_FAILURE:
            return {
                ...state,
                notification: {type: 'error', message:'Erro ao salvar Resquisição de Serviço'},
                loading: false
            }
        
        case actions.ON_CHANGE_REPORT_FORM:
            return state

        case actions.ON_CHANGE_REPORT_FORM_SUCCESS:
            return {
                ...state,
                formFields: {
                    ...state.formFields,
                    ...action.payload
                },
                notification: {}
            }
        
        case actions.GET_REPORT_BY_ID:
            return {
                ...state,
                loading: true,
                notification: {}
            }
        
        case actions.GET_REPORT_BY_ID_SUCCESS:
            return {
                ...state,
                report: action.payload,
                formFields: setFormFields(action.payload),
                notification: {},
                loading: false
            }

        case actions.GET_REPORT_BY_ID_FAILURE:
            return {
                ...state,
                notification: {type: 'error', message:'Erro ao carregar requisição de serviço'},
                loading: false
            }
        
        case actions.UPDATE_REPORT:
            return {
                ...state,
                loading: true,
                notification: {}
            }
        
        case actions.UPDATE_REPORT_SUCCESS:
            return {
                ...state,
                notification: {type: (action.payload.status === '1' ? 'success': 'error'), message: action.payload.message},
                formFields: INIT_STATE.formFields,
                loading: false
            }

        case actions.UPDATE_REPORT_FAILURE:
            return {
                ...state,
                notification: {type: 'error', message:'Erro ao salvar requisição de serviço'},
                loading: false
            }

        case actions.DELETE_REPORT:
            return {
                ...state,
                loading: true,
                notification: {}
            }
        
        case actions.DELETE_REPORT_SUCCESS:
            return {
                ...state,
                notification: {type: (action.payload.status === '1' ? 'success': 'error'), message: action.payload.message},
                loading: false
            }

        case actions.DELETE_REPORT_FAILURE:
            return {
                ...state,
                notification: {type: 'error', message:'Erro ao excluir requisição de serviço'},
                loading: false
            }
        case actions.PREVIEW_REPORT:
        case actions.PREVIEW_REPORT_SUCCESS:
            return state
        
        default:
        return state
    }
}
