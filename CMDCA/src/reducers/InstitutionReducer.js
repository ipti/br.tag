import  * as actions from 'Actions/InstitutionTypes';

function setFormFields(data){
    return{
        _id: data._id,
        name: data.name,
        type: data.type,
        address: data.address,
        phone: data.phone,
        email: data.email,
    }
}

const INIT_STATE ={
    institutions:[],
    institution: {},
    pagination: {
        currentPage: 1,
        perPage: 15,
        totalItens: 0,
        totalPages: 0
    },
    formFields:{
        _id: '',
        name: '',
        type: '',
        address: '',
        phone: '',
        email: ''
    },
    loading: false,
    notification: {}
}

export default (state = INIT_STATE, action) => {
    switch (action.type) {
        case actions.GET_INSTITUTION:
            return {
                ...state,
                loading: true,
                notification: {}
            }
        
        case actions.GET_INSTITUTION_SUCCESS:
            return {
                ...state,
                institutions: action.payload.institutions,
                pagination: action.payload.pagination,
                notification: {type: 'success', message:'Instituições carregadas com sucesso'},
                loading: false
            }

        case actions.GET_INSTITUTION_FAILURE:
            return {
                ...state,
                notification: {type: 'error', message:'Erro ao carregar instituições'},
                loading: false
            }

        case actions.SAVE_INSTITUTION:
            return {
                ...state,
                loading: true,
                notification: {}
            }
        
        case actions.SAVE_INSTITUTION_SUCCESS:
            return {
                ...state,
                notification: {type: (action.payload.status === '1' ? 'success': 'error'), message: action.payload.message},
                formFields: (action.payload.status === '1' ? INIT_STATE.formFields : state.formFields),
                loading: false
            }

        case actions.SAVE_INSTITUTION_FAILURE:
            return {
                ...state,
                notification: {type: 'error', message:'Erro ao salvar instituição'},
                loading: false
            }
        
        case actions.ON_CHANGE_INSTITUTION_FORM:
            return state

        case actions.ON_CHANGE_INSTITUTION_FORM_SUCCESS:
            return {
                ...state,
                formFields: {
                    ...state.formFields,
                    ...action.payload
                },
                notification: {}
            }
        
        case actions.GET_INSTITUTION_BY_ID:
            return {
                ...state,
                loading: true,
                institution: {}
            }
        
        case actions.GET_INSTITUTION_BY_ID_SUCCESS:
            return {
                ...state,
                institution: action.payload,
                formFields: setFormFields(action.payload),
                notification: {},
                loading: false
            }

        case actions.GET_INSTITUTION_BY_ID_FAILURE:
            return {
                ...state,
                institution: {type: 'error', message:'Erro ao carregar instituição'},
                loading: false
            }
        
        case actions.UPDATE_INSTITUTION:
            return {
                ...state,
                loading: true,
                notification: {}
            }
        
        case actions.UPDATE_INSTITUTION_SUCCESS:
            return {
                ...state,
                notification: {type: (action.payload.status === '1' ? 'success': 'error'), message: action.payload.message},
                formFields: INIT_STATE.formFields,
                loading: false
            }

        case actions.UPDATE_INSTITUTION_FAILURE:
            return {
                ...state,
                notification: {type: 'error', message:'Erro ao salvar instituição'},
                loading: false
            }
        
        
        default:
        return state
    }
}
