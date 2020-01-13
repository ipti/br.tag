import React from "react";
import PropTypes from "prop-types";
import TextField from "@material-ui/core/TextField";
import PersonOutline from "@material-ui/icons/PersonOutline";
import LockOpen from "@material-ui/icons/LockOpen";
import InputAdornment from "@material-ui/core/InputAdornment";
import Grid from "@material-ui/core/Grid";
import LoginImg from "../../assets/images/security-login.png";
import { makeStyles } from "@material-ui/core/styles";
import { Formik, Form } from "formik";
import { ButtonPurple } from "../../components/Buttons";
import { Link } from "react-router-dom";

import styles from "./styles";

const useStyles = makeStyles(styles);

const Login = props => {
  const classes = useStyles();
  let isValid = props.isValid;

  return (
    <Grid className={classes.root} container direction="row" justify="flex-end">
      <Grid className={classes.contentLeft} item md={8} sm={6} xs={12}>
        <Grid
          className={`${classes.marginTopContentLeft} ${classes.titleBig}`}
          container
          alignItems="center"
        >
          <Grid item md={2} sm={2} xs={2}></Grid>
          <Grid className={`${classes.titleBig}`} item md={9} sm={9} xs={12}>
            Bem-Vindo
          </Grid>
          <Grid item md={2} sm={2} xs={2}></Grid>
          <Grid className={classes.titleBig} item md={9} sm={9} xs={12}>
            ao Matrícula Online
          </Grid>
        </Grid>
        <Grid container direction="row">
          <Grid item md={2} sm={2}></Grid>
          <Grid item md={7} sm={7}>
            Efetue o login ao lado para acessar sua conta, caso não possua
            realize seu cadastro
          </Grid>
        </Grid>
        <Grid className={classes.boxRegister} container direction="row">
          <Grid item md={2} sm={2}></Grid>
          <Grid item md={9}>
            <Link className={classes.linkRegister} to="#">
              CADASTRE-SE
            </Link>
          </Grid>
        </Grid>
      </Grid>

      <Grid className={classes.contentRight} item md={4} sm={6} xs={12}>
        <Grid container direction="row" justify="center" alignItems="center">
          <Grid item md={12}>
            <p className={classes.titleLogin}>Matricula Online </p>
          </Grid>
        </Grid>
        <Grid
          className={classes.imageLogin}
          container
          direction="row"
          justify="center"
          alignItems="center"
        >
          <Grid item md={2}>
            <div>
              <img src={LoginImg} alt="" />
            </div>
          </Grid>
        </Grid>
        <Formik
          initialValues={props.initialValues}
          onSubmit={props.onSubmit}
          validationSchema={props.validationSchema}
          validateOnChange={false}
        >
          {props => {
            return (
              <Form>
                <Grid container direction="row" justify="center">
                  <Grid item md={8} sm={8}>
                    <TextField
                      name="username"
                      onChange={props.handleChange}
                      variant="outlined"
                      InputProps={{
                        startAdornment: (
                          <InputAdornment position="start">
                            <PersonOutline className={classes.colorIcon} />
                          </InputAdornment>
                        )
                      }}
                    />
                    <div className={classes.formFieldError}>
                      {props.errors.username}
                    </div>
                  </Grid>
                </Grid>
                <Grid container direction="row" justify="center">
                  <Grid item md={8} sm={8}>
                    <TextField
                      name="password"
                      onChange={props.handleChange}
                      variant="outlined"
                      type="password"
                      InputProps={{
                        startAdornment: (
                          <InputAdornment position="start">
                            <LockOpen className={classes.colorIcon} />
                          </InputAdornment>
                        )
                      }}
                    />
                    <div className={classes.formFieldError}>
                      {props.errors.password}
                    </div>
                  </Grid>
                </Grid>
                <Grid
                  className={classes.buttonLogin}
                  container
                  direction="row"
                  alignItems="center"
                  justify="center"
                >
                  <Grid
                    className={`${classes.boxError} ${classes.textCenter}`}
                    item
                    md={12}
                    sm={12}
                  >
                    <div>
                      {!isValid ? "Usuário e/ou Senha inválido(s)" : ""}
                    </div>
                  </Grid>
                  <Grid item md={6} sm={6}>
                    <ButtonPurple
                      onClick={props.handleSubmit}
                      type="submit"
                      title="Entrar"
                    />
                  </Grid>
                </Grid>
                <Grid container direction="row">
                  <Grid
                    className={`${classes.resetPassword} ${classes.textCenter}`}
                    item
                    md={12}
                    sm={12}
                  >
                    Esqueceu sua senha?
                    <Link className={classes.link} to="#">
                      clique aqui
                    </Link>
                  </Grid>
                </Grid>
              </Form>
            );
          }}
        </Formik>
      </Grid>
    </Grid>
  );
};

Login.propTypes = {
  username: PropTypes.string,
  password: PropTypes.string
};

export default Login;
