import * as actions from './FollowTypes';

export const getStudents = (students) => ({
  type: actions.GET_STUDENTS,
  payload: students
});

export const getError = (error) => ({
  type: actions.GET_ERROR_STUDENT,
  payload: 'Erro: ' + error + '. Por favor, tente novamente.'
});
