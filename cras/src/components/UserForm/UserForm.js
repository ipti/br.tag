import React, { useState } from 'react';

//  Material UI
import { FormGroup, Typography } from '@material-ui/core';
import InputLabel from '@material-ui/core/InputLabel';
import TextField from '@material-ui/core/TextField';
import { makeStyles } from '@material-ui/core/styles';

// Components
import Button from '../../components/Button';

// Services
import api from '../../services/api';

// Third party
import { Formik, Form } from 'formik';
import * as yup from 'yup';

// Styles
import { styles } from './styles';

const useStyles = makeStyles(styles);

const UserForm = ({ onClose, onSuccess }) => {
  const classes = useStyles();
  const [isValid, setValid] = useState(true);

  const initialValues = {
    username: '',
    password: '',
    confirmPassword: ''
  };

  const validationSchema = yup.object().shape({
    username: yup.string().required('Campo obrigatório').email('E-mail inválido'),
    password: yup
      .string()
      .required('Campo obrigatório')
      .min(6, 'A senha deve ter no mínimo 6 caracteres'),
    confirmPassword: yup
      .string()
      .oneOf([yup.ref('password'), null], 'As senhas não conferem')
      .required('Campo obrigatório')
      .min(6, 'A senha deve ter no mínimo 6 caracteres')
      .test('password-strong', 'A senha dever conter números e letras', (value) => {
        return !value ? /([a-zA-Z])/g.test(String(value)) && /\d/g.test(value) : true;
      })
  });

  const handleSubmit = (values) => {
    const params = {
      email: values.username,
      credential: {
        username: values.username,
        password: values.password
      }
    };

    api.post(`/signup`, params).then(() => {
      setValid(true);
      onSuccess();
    }).catch(() => {
      setValid(false);
    });
  };

  const handleCancel = () => {
    onClose();
  };

  return (
    <Formik
      initialValues={initialValues}
      onSubmit={handleSubmit}
      validationSchema={validationSchema}
    >
      {({ touched, errors, handleChange, handleSubmit }) => {
        return (
          <Form>
            <FormGroup className={classes.formGroup}>
              <InputLabel>E-mail</InputLabel>
              <TextField
                name="username"
                autoComplete="off"
                onChange={handleChange}
                variant="outlined"
                error={touched.username && !!errors.username}
                helperText={touched.username && errors.username}
              />
            </FormGroup>
            <FormGroup className={classes.formGroup}>
              <InputLabel>Senha</InputLabel>
              <TextField
                name="password"
                onChange={handleChange}
                variant="outlined"
                type="password"
                error={touched.password && !!errors.password}
                helperText={touched.password && errors.password}
              />
            </FormGroup>
            <FormGroup className={classes.formGroup}>
              <InputLabel>Confirmar senha</InputLabel>
              <TextField
                name="confirmPassword"
                onChange={handleChange}
                variant="outlined"
                type="password"
                error={touched.confirmPassword && !!errors.confirmPassword}
                helperText={touched.confirmPassword && errors.confirmPassword}
              />
            </FormGroup>
            {!isValid && (
              <FormGroup className={classes.formGroup}>
                <Typography className={classes.error} align="center">
                  Erro ao cadastrar usuário, tente novamente.
                </Typography>
              </FormGroup>
            )}
            <FormGroup className={classes.formGroupButton}>
              <Button type="button" variant="outlined" color="secondary" onClick={handleCancel}>
                Cancelar
              </Button>
              <Button type="submit" onClick={handleSubmit}>
                Cadastrar
              </Button>
            </FormGroup>
          </Form>
        );
      }}
    </Formik>
  );
};

export default UserForm;
