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

const Create = props => {
  const classes = useStyles();
  let isValid = props.isValid;

  return (
    <>
      <Grid container direction="row">
        <Grid
          className={classes.boxTitlePagination}
          item
          md={12}
          sm={12}
          xs={12}
        >
          <h1 className={`${classes.title} ${classes.floatLeft}`}>
            Cronograma - Adicionar
          </h1>
        </Grid>
      </Grid>
      <Grid container direction="row">
        <Grid item md={4} sm={6} xs={12}>
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
                        name="internalTransferDateStart"
                        onChange={props.handleChange}
                        variant="outlined"
                      />
                      <div className={classes.formFieldError}>
                        {props.errors.internalTransferDateStart}
                      </div>
                    </Grid>
                  </Grid>
                  <Grid container direction="row" justify="center">
                    <Grid item md={8} sm={8}>
                      <TextField
                        name="internalTransferDateEnd"
                        onChange={props.handleChange}
                        variant="outlined"
                        type="text"
                      />
                      <div className={classes.formFieldError}>
                        {props.errors.internalTransferDateEnd}
                      </div>
                    </Grid>
                  </Grid>
                  <Grid
                    container
                    direction="row"
                    alignItems="center"
                    justify="center"
                  >
                    <Grid item md={6} sm={6}>
                      <ButtonPurple
                        onClick={props.handleSubmit}
                        type="submit"
                        title="Salvar"
                      />
                    </Grid>
                  </Grid>
                </Form>
              );
            }}
          </Formik>
        </Grid>
      </Grid>
    </>
  );
};

Create.propTypes = {
  username: PropTypes.string,
  password: PropTypes.string
};

export default Create;
