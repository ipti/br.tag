const ValidationSignup = (state) => {
  let errors = {};
  let msg = 'Campo obrigatório';

  if (state.email === '') {
    errors.email = msg;
  }

  if (state.password === '') {
    errors.password = msg;
  }

  if (state.password === '') {
    errors.confirm_password = msg;
  }

  if (state.email !== '' && !/^[a-zA-Z0-9]+@[a-zA-Z0-9]+\.[A-Za-z]+$/.test(state.email)) {
    errors.email = 'E-mail inválido';
  }

  if (state.password !== '' && state.password !== '' && state.password !== state.confirm_password) {
    errors.confirm_password = 'Senhas são diferentes';
  } else {
    if (state.password.length < 6 || state.password.length > 20) {
      errors.password = 'Senha deve ter no mínimo 6 carateres e no máximo 20';
    }
  }

  return errors;
};

export default ValidationSignup;
