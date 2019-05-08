import  * as actions from 'Actions/PeopleTypes';

function setFormFields(data){
    return{
        _id: data._id,
        name: data.name,
        birthday: data.birthday,
        father: data.father,
        mother: data.mother,
        nickname: data.nickname,
        sex: data.sex,
        rg: data.rg,
        cpf: data.cpf,
        civilStatus: data.civilStatus,
        nacionality: data.nacionality,
        placeBirthday: data.placeBirthday,
        profession: data.profession,
        scholarity: data.scholarity,
        street: data.address.street,
        number: data.address.number,
        complement: data.address.complement,
        neighborhood: data.address.neighborhood,
        zip: data.address.zip,
        city: data.address.city,
        state: data.address.state,
        country: data.address.country,
    }
}

const INIT_STATE ={
    peoples:[],
    people: {},
    pagination: {
        currentPage: 1,
        perPage: 15,
        totalItens: 0,
        totalPages: 0
    },
    formFields:{
        _id: '',
        name: '',
        birthday: '',
        father: '',
        mother: '',
        nickname: '',
        sex: '',
        rg: '',
        cpf: '',
        civilStatus: '',
        nacionality: '',
        placeBirthday: '',
        profession: '',
        scholarity: '',
        street: '',
        number: '',
        complement: '',
        neighborhood: '',
        zip: '',
        city: '',
        state: '',
        country: '',
    },
    loading: false,
    notification: {}
}

export default (state = INIT_STATE, action) => {
    switch (action.type) {
        case actions.GET_PEOPLE:
            return {
                ...state,
                loading: true,
                notification: {}
            }
        
        case actions.GET_PEOPLE_SUCCESS:
            return {
                ...state,
                peoples: action.payload.peoples,
                pagination: action.payload.pagination,
                notification: {type: 'success', message:'Pessoas carregadas com sucesso'},
                loading: false
            }

        case actions.GET_PEOPLE_FAILURE:
            return {
                ...state,
                notification: {type: 'error', message:'Erro ao carregar pessoas'},
                loading: false
            }

        case actions.SAVE_PEOPLE:
            return {
                ...state,
                loading: true,
                notification: {}
            }
        
        case actions.SAVE_PEOPLE_SUCCESS:
            return {
                ...state,
                notification: {type: (action.payload.status === '1' ? 'success': 'error'), message: action.payload.message},
                formFields: INIT_STATE.formFields,
                loading: false
            }

        case actions.SAVE_PEOPLE_FAILURE:
            return {
                ...state,
                notification: {type: 'error', message:'Erro ao salvar pessoa'},
                loading: false
            }
        
        case actions.ON_CHANGE_PEOPLE_FORM:
            return state

        case actions.ON_CHANGE_PEOPLE_FORM_SUCCESS:
            return {
                ...state,
                formFields: {
                    ...state.formFields,
                    ...action.payload
                }
            }
        
        case actions.GET_PEOPLE_BY_ID:
            return {
                ...state,
                loading: true,
                notification: {}
            }
        
        case actions.GET_PEOPLE_BY_ID_SUCCESS:
            return {
                ...state,
                people: action.payload,
                formFields: setFormFields(action.payload),
                notification: {},
                loading: false
            }

        case actions.GET_PEOPLE_BY_ID_FAILURE:
            return {
                ...state,
                notification: {type: 'error', message:'Erro ao carregar pessoa'},
                loading: false
            }
        
        case actions.UPDATE_PEOPLE:
            return {
                ...state,
                loading: true,
                notification: {}
            }
        
        case actions.UPDATE_PEOPLE_SUCCESS:
            return {
                ...state,
                notification: {type: (action.payload.status === '1' ? 'success': 'error'), message: action.payload.message},
                formFields: INIT_STATE.formFields,
                loading: false
            }

        case actions.UPDATE_PEOPLE_FAILURE:
            return {
                ...state,
                notification: {type: 'error', message:'Erro ao salvar pessoa'},
                loading: false
            }
        
        
        default:
        return state
    }
}