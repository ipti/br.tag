import React, { useState } from 'react';

//  Material UI
import { FormGroup, Typography } from '@material-ui/core';
import TextField from '@material-ui/core/TextField';
import InputAdornment from '@material-ui/core/InputAdornment';
import PersonOutline from '@material-ui/icons/PersonOutline';
import LockOpen from '@material-ui/icons/LockOpen';
import { makeStyles } from '@material-ui/core/styles';

// Components
import Button from '../../../../components/Button';

// Third party
import { Formik, Form } from 'formik';
import * as Yup from 'yup';
import { useHistory } from 'react-router-dom';

// Services
import api from '../../../../services/api';
import { login } from '../../../../services/auth';

// Styles
import { styles } from './styles';

const useStyles = makeStyles(styles);

const LoginForm = () => {
  const classes = useStyles();
  const [isValid, setValid] = useState(true);

  const history = useHistory();

  const initialValues = {
    username: '',
    password: ''
  };

  const validationSchema = Yup.object().shape({
    username: Yup.string().required('Campo obrigatório'),
    password: Yup.string()
      .min(6, 'A senha deve ter no mínimo 6 caracteres')
      .required('Campo obrigatório')
  });

  const handleSubmit = (values) => {
    setValid(true);

    api
      .post('/user/login', values)
      .then(({ data }) => {
        login(data.data);
        history.push('/');
      })
      .catch(() => {
        setValid(false);
      });
  };

  return (
    <Formik
      initialValues={initialValues}
      onSubmit={handleSubmit}
      validationSchema={validationSchema}
    >
      {({ touched, errors, handleChange, handleSubmit }) => (
        <Form>
          <FormGroup className={classes.formGroup}>
            <TextField
              name="username"
              autoComplete="off"
              onChange={handleChange}
              variant="outlined"
              placeholder="Usuário"
              error={touched.username && !!errors.username}
              helperText={touched.username && errors.username}
              InputProps={{
                startAdornment: (
                  <InputAdornment position="start">
                    <PersonOutline className={classes.icon} />
                  </InputAdornment>
                )
              }}
            />
          </FormGroup>
          <FormGroup className={classes.formGroup}>
            <TextField
              name="password"
              onChange={handleChange}
              variant="outlined"
              type="password"
              placeholder="Senha"
              error={touched.password && !!errors.password}
              helperText={touched.password && errors.password}
              InputProps={{
                startAdornment: (
                  <InputAdornment position="start">
                    <LockOpen className={classes.icon} />
                  </InputAdornment>
                )
              }}
            />
          </FormGroup>
          <FormGroup className={classes.formGroup}>
            {!isValid && (
              <Typography className={classes.error} align="center">
                Usuário ou senha inválido
              </Typography>
            )}
            <Button size="medium" type="submit" onClick={handleSubmit}>
              Entrar
            </Button>
          </FormGroup>
        </Form>
      )}
    </Formik>
  );
};

export default LoginForm;
