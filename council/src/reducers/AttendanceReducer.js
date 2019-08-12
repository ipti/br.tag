import  * as actions from 'Actions/AttendanceTypes';

const INIT_STATE ={
    attendances:[],
    attendance: {},
    types: [],
    formFields:{
        _id: '',
        type: '',
        date: '',
        time: '',
        place: '',
        person: '',
    },
    loading: false,
    notification: {}
}

export default (state = INIT_STATE, action) => {
    switch (action.type) {
        case actions.GET_ATTENDANCE:
        case actions.SAVE_ATTENDANCE:
        case actions.GET_ATTENDANCE_TYPE:
            return {
                ...state,
                loading: true,
                notification: {}
            }
        
        case actions.GET_ATTENDANCE_SUCCESS:
            return {
                ...state,
                attendances: action.payload.attendances,
                loading: false
            }

        case actions.GET_ATTENDANCE_FAILURE:
            return {
                ...state,
                notification: {type: 'error', message:'Erro ao carregar Atendimento'},
                loading: false
            }

        case actions.GET_ATTENDANCE_TYPE_SUCCESS:
            return {
                ...state,
                types: action.payload,
                loading: false
            }

        case actions.GET_ATTENDANCE_TYPE_FAILURE:
            return {
                ...state,
                notification: {type: 'error', message:'Erro ao carregar tipos de atendimento'},
                loading: false
            }
        
        case actions.SAVE_ATTENDANCE_SUCCESS:
            return {
                ...state,
                notification: {type: (action.payload.status === '1' ? 'success': 'error'), message: action.payload.message},
                formFields: (action.payload.status === '1' ? INIT_STATE.formFields : state.formFields),
                loading: false
            }

        case actions.SAVE_ATTENDANCE_FAILURE:
            return {
                ...state,
                notification: {type: 'error', message:'Erro ao salvar Atendimento'},
                loading: false
            }
        
        case actions.ON_CHANGE_ATTENDANCE_FORM:
            return state

        case actions.ON_CHANGE_ATTENDANCE_FORM_SUCCESS:
            return {
                ...state,
                formFields: {
                    ...state.formFields,
                    ...action.payload
                },
                notification: {}
            }
        
        
        default:
        return state
    }
}
