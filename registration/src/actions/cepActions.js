import * as actions from "./cepTypes";

export const getAddress = response => ({
  type: actions.GET_ADDRESS,
  payload: response
});


export const getError = error => ({
  type: actions.GET_ERROR_ADDRESS,
  payload: "Erro: " + error + ". Por favor, tente novamente."
});

