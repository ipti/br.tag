import React from "react";
import Grid from "@material-ui/core/Grid";
import { makeStyles } from "@material-ui/core/styles";
import TextField from "@material-ui/core/TextField";
import { Formik, Form } from "formik";
import homeImg from "../../assets/images/illustration-home.png";
import { ButtonPurple } from "../../components/Buttons";
import * as Yup from "yup";
import styles from "./styles";

const useStyles = makeStyles(styles);

const StepTwo = props => {
  const classes = useStyles();

  const validationSchema = Yup.object().shape({
    numRegistration: Yup.string()
      .nullable()
      .required("Campo é obrigatório!")
  });

  return (
    <>
      <Grid
        className={`${classes.contentStart} ${classes.contentBond}`}
        container
        direction="row"
        justify="center"
        alignItems="center"
      >
        <Grid item md={8} sm={12} xs={12}>
          <img src={homeImg} alt="" />
        </Grid>
        <Grid item md={8} sm={12} xs={12}>
          <h1>Possui Vínculo</h1>
          <p>Informe o número de matrícula</p>
          <p>do ano anterior abaixo</p>
        </Grid>
      </Grid>
      <Formik
        initialValues={{
          numRegistration: ""
        }}
        validationSchema={validationSchema}
        onSubmit={values => props.next(3, values)}
        validateOnChange={false}
        enableReinitialize
      >
        {props => {
          return (
            <Form>
              <Grid
                className={`${classes.marginTop} ${classes.contentMain}`}
                container
                direction="row"
                justify="center"
                alignItems="center"
              >
                <Grid item md={3} sm={6} xs={8}>
                  <TextField
                    label="Nº Matrícula"
                    name="numRegistration"
                    onChange={props.handleChange}
                    id="outlined-size-small"
                    variant="outlined"
                    className={classes.textField}
                  />

                  <div className={classes.formFieldError}>
                    {props.errors.numRegistration}
                  </div>
                </Grid>
              </Grid>
              <Grid
                className={`${classes.marginTop} ${classes.marginButtom}`}
                justify="center"
                alignItems="center"
                container
                direction="row"
              >
                <Grid item md={2} sm={6} xs={6}>
                  <ButtonPurple
                    onClick={props.handleSubmit}
                    type="submit"
                    title="Continuar"
                  />
                </Grid>
              </Grid>
            </Form>
          );
        }}
      </Formik>
    </>
  );
};

export default StepTwo;
