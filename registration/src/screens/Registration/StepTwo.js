import React from "react";

// Material UI
import {
  Grid,
  TextField,
  FormControl,
  FormHelperText
} from "@material-ui/core";

import { makeStyles } from "@material-ui/core/styles";

// Third party
import { Formik, Form } from "formik";
import * as Yup from "yup";

// Components
import { ButtonPurple } from "../../components/Buttons";
import Loading from "../../components/Loading/CircularLoadingButtomActions";

// Assets
import homeImg from "../../assets/images/illustration-home.png";

// Styles
import styles from "./styles";
import { useHistory } from "react-router";

const useStyles = makeStyles(styles);

const StepTwo = props => {
  const classes = useStyles();

  const history = useHistory()

  const validationSchema = Yup.object().shape({
    numRegistration: Yup.string()
      .nullable()
      .required("Campo obrigatório!")
  });

  return (
    <>
      <Grid
        className={`${classes.contentStart}`}
        container
        direction="row"
        justifyContent="center"
        alignItems="center"
      >
        <Grid item xs={12}>
          <img src={homeImg} alt="" />
        </Grid>
        <Grid item xs={12}>
          <h1>Possui Vínculo</h1>
          <p>
            Informe o número de matrícula <br /> do ano anterior abaixo
          </p>
        </Grid>
      </Grid>

      <Formik
        initialValues={{
          numRegistration: ""
        }}
        validationSchema={validationSchema}
        validateOnChange={false}
        enableReinitialize
      >
        {({ errors, touched, handleChange, values }) => {
          const errorList = {
            numRegistration: touched.numRegistration && errors.numRegistration
          };

          return (
            <Form>
              <Grid
                className={`${classes.marginTop} ${classes.contentMain}`}
                container
                direction="row"
                justifyContent="center"
                alignItems="center"
              >
                <Grid item xs={12}>
                  <FormControl
                    component="fieldset"
                    className={classes.formControl}
                    error={errorList.numRegistration}
                  >
                    <TextField
                      label="Nº Matrícula"
                      name="numRegistration"
                      onChange={handleChange}
                      id="outlined-size-small"
                      variant="outlined"
                      className={classes.textField}
                      error={errorList.numRegistration}
                    />

                    <FormHelperText>{errorList.numRegistration}</FormHelperText>
                  </FormControl>
                </Grid>
              </Grid>
              <Grid
                className={`${classes.marginTop} ${classes.marginButtom}`}
                justifyContent="center"
                alignItems="center"
                container
                direction="row"
              >
                <Grid item xs={6}>
                  {!props.loadingButtom ? (
                    <ButtonPurple
                      onClick={()=> history.push(`/matricula/${values.numRegistration}`)}
                      type="submit"
                      title="Continuar"
                    />
                  ) : (
                    <Loading />
                  )}
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
