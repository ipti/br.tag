import { isBefore, parseISO, format } from 'date-fns';

const ValidationSchedule = (state) => {
  let errors = {};
  let requiredMessage = 'Campo obrigatório';
  let requiredDateMessage = 'Campo data final menor que o inicial';
  let requiredDateMessageStage =
    'Campo data início etapa atual menor que o final da etapa anterior';
  let requiredMessageYearEquals = 'Ano da data da etapa deve ser igual ao ano definido';

  let keys = Object.keys(state);

  keys.map((key) => {
    if (state[key] === '') {
      errors[key] = requiredMessage;
    }

    if (state.year !== '' && state[key].length === 8) {
      let date = parseISO(state[key]);
      let yearStage = format(date, 'YYYY');

      if (yearStage !== state.year) {
        errors[key] = requiredMessageYearEquals;
      }
    }
    return key;
  });

  if (
    state.dateStartHealth1 !== '' &&
    state.dateEndHealth1 !== '' &&
    isBefore(new Date(state.dateEndHealth1), new Date(state.dateStartHealth1))
  ) {
    errors['dateEndHealth1'] = requiredDateMessage;
  }

  if (
    state.dateStartHealth2 !== '' &&
    state.dateEndtHealth2 !== '' &&
    isBefore(new Date(state.dateEndHealth2), new Date(state.dateStartHealth2))
  ) {
    errors['dateEndHealth2'] = requiredDateMessage;
  }

  if (
    state.dateStartEducation1 !== '' &&
    state.dateEndEducation1 !== '' &&
    isBefore(new Date(state.dateEndEducation1), new Date(state.dateStartEducation1))
  ) {
    errors['dateEndEducation1'] = requiredDateMessage;
  }

  if (
    state.dateStartEducation2 !== '' &&
    state.dateEndEducation2 !== '' &&
    isBefore(new Date(state.dateEndEducation2), new Date(state.dateStartEducation2))
  ) {
    errors['dateEndEducation2'] = requiredDateMessage;
  }

  if (
    state.dateStartEducation3 !== '' &&
    state.dateEndEducation3 !== '' &&
    isBefore(new Date(state.dateEndEducation3), new Date(state.dateStartEducation3))
  ) {
    errors['dateEndEducation3'] = requiredDateMessage;
  }

  if (
    state.dateEndHealth1 !== '' &&
    state.dateStartHealth2 !== '' &&
    isBefore(new Date(state.dateStartHealth2), new Date(state.dateEndHealth1))
  ) {
    errors['dateStartHealth2'] = requiredDateMessageStage;
  }

  if (
    state.dateEndEducation1 !== '' &&
    state.dateStartEducation2 !== '' &&
    isBefore(new Date(state.dateStartEducation2), new Date(state.dateEndEducation1))
  ) {
    errors['dateStartEducation2'] = requiredDateMessageStage;
  }

  if (
    state.dateEndEducation2 !== '' &&
    state.dateStartEducation3 !== '' &&
    isBefore(new Date(state.dateStartEducation3), new Date(state.dateEndEducation2))
  ) {
    errors['dateStartEducation3'] = requiredDateMessageStage;
  }

  return errors;
};

export default ValidationSchedule;
