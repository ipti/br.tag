import * as actions from "../actions/cepTypes";

const initialState = {
  addresses: [],

 
  msgError: "",
  loading: true,
 
};

export default (state = initialState, action) => {
  switch (action.type) {
   
    case actions.GET_ADDRESS:
      return {
        ...state,
        addresses: action.payload,
        loading: true
      };
    
    
    case actions.GET_ERROR_ADDRESS:
      return {
        ...state,
        msgError: action.payload,
        loading: false
      };
    default:
      return state;
  }
};
