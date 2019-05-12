import  * as actions from 'Actions/HousingTypes';

function setFormFields(data){
    return{
        _id: data._id,
        sender: data.sender,
        child: data.child,
        receiver: data.receiver,
        motive: data.motive,
    }
}

const INIT_STATE ={
    housings:[],
    housing: {},
    pagination: {
        currentPage: 1,
        perPage: 15,
        totalItens: 0,
        totalPages: 0
    },
    formFields:{
        _id: '',
        sender: '',
        child: '',
        receiver: '',
        motive: '',
    },
    loading: false,
    notification: {}
}

export default (state = INIT_STATE, action) => {
    switch (action.type) {
        case actions.GET_HOUSING:
            return {
                ...state,
                loading: true,
                notification: {}
            }
        
        case actions.GET_HOUSING_SUCCESS:
            return {
                ...state,
                housings: action.payload.housings,
                pagination: action.payload.pagination,
                notification: {type: 'success', message:'Termo de Abrigramento carregadas com sucesso'},
                loading: false
            }

        case actions.GET_HOUSING_FAILURE:
            return {
                ...state,
                notification: {type: 'error', message:'Erro ao carregar Termo de Abrigramento'},
                loading: false
            }

        case actions.SAVE_HOUSING:
            return {
                ...state,
                loading: true,
                notification: {}
            }
        
        case actions.SAVE_HOUSING_SUCCESS:
            return {
                ...state,
                notification: {type: (action.payload.status === '1' ? 'success': 'error'), message: action.payload.message},
                formFields: (action.payload.status === '1' ? INIT_STATE.formFields : state.formFields),
                loading: false
            }

        case actions.SAVE_HOUSING_FAILURE:
            return {
                ...state,
                notification: {type: 'error', message:'Erro ao salvar Termo de Abrigramento'},
                loading: false
            }
        
        case actions.ON_CHANGE_HOUSING_FORM:
            return state

        case actions.ON_CHANGE_HOUSING_FORM_SUCCESS:
            return {
                ...state,
                formFields: {
                    ...state.formFields,
                    ...action.payload
                },
                notification: {}
            }
        
        case actions.GET_HOUSING_BY_ID:
            return {
                ...state,
                loading: true,
                housing: {}
            }
        
        case actions.GET_HOUSING_BY_ID_SUCCESS:
            return {
                ...state,
                housing: action.payload,
                formFields: setFormFields(action.payload),
                notification: {},
                loading: false
            }

        case actions.GET_HOUSING_BY_ID_FAILURE:
            return {
                ...state,
                housing: {type: 'error', message:'Erro ao carregar Termo de Abrigramento'},
                loading: false
            }
        
        case actions.UPDATE_HOUSING:
            return {
                ...state,
                loading: true,
                notification: {}
            }
        
        case actions.UPDATE_HOUSING_SUCCESS:
            return {
                ...state,
                notification: {type: (action.payload.status === '1' ? 'success': 'error'), message: action.payload.message},
                formFields: INIT_STATE.formFields,
                loading: false
            }

        case actions.UPDATE_HOUSING_FAILURE:
            return {
                ...state,
                notification: {type: 'error', message:'Erro ao salvar Termo de Abrigramento'},
                loading: false
            }

        case actions.DELETE_HOUSING:
            return {
                ...state,
                loading: true,
                notification: {}
            }
        
        case actions.DELETE_HOUSING_SUCCESS:
            return {
                ...state,
                notification: {type: (action.payload.status === '1' ? 'success': 'error'), message: action.payload.message},
                loading: false
            }

        case actions.DELETE_HOUSING_FAILURE:
            return {
                ...state,
                notification: {type: 'error', message:'Erro ao excluir Termo de Abrigramento'},
                loading: false
            }
        case actions.PREVIEW_HOUSING:
        case actions.PREVIEW_HOUSING_SUCCESS:
            return state
        
        default:
        return state
    }
}
