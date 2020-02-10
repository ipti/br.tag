import React from "react";
import PropTypes from "prop-types";
import TextField from "@material-ui/core/TextField";
import Grid from "@material-ui/core/Grid";
import { makeStyles } from "@material-ui/core/styles";
import { FormLabel, FormControl } from "@material-ui/core";
import { Formik, Form } from "formik";
import { TitleWithLine } from "../../components/Titles";
import { BoxVacancies } from "../../components/Boxes";
import { BoxRegistration } from "../../components/Boxes";
import { ButtonPurple } from "../../components/Buttons";

import styles from "./styles";

const useStyles = makeStyles(theme => styles);

const Create = props => {
  const classes = useStyles();
  const {
    initialValues,
    handleSubmit,
    validationSchema,
    data,
    baseLink
  } = props;

  const registrations = () => {
    if (data) {
      return data.registrations.map((registration, index) => {
        return (
          <BoxRegistration
            link={`${baseLink}/${registration._id}`}
            key={index}
            name={registration.student.name}
            sex={registration.student.sex}
            confirmed={registration.confirmed}
            md={4}
            sm={4}
          />
        );
      });
    }
    return [];
  };

  return (
    <>
      <Grid container direction="row">
        <TitleWithLine title={data && data.name} />
      </Grid>
      <Formik
        initialValues={initialValues}
        onSubmit={handleSubmit}
        validationSchema={validationSchema}
        validateOnChange={false}
        enableReinitialize
      >
        {props => {
          return (
            <Form>
              <Grid
                className={classes.boxContent}
                container
                direction="row"
                spacing={3}
              >
                <Grid item md={6} sm={6}>
                  <FormControl
                    component="fieldset"
                    className={classes.formControl}
                  >
                    <FormLabel>Número de Vagas</FormLabel>
                    <TextField
                      id="outlined-basic"
                      variant="outlined"
                      name="vacancies"
                      onChange={props.handleChange}
                      className={classes.textField}
                    />

                    <div className={classes.formFieldError}>
                      {props.errors.year}
                    </div>
                  </FormControl>
                  <Grid container direction="row">
                    <Grid item md={4} sm={6}>
                      <ButtonPurple
                        onClick={props.handleSubmit}
                        type="submit"
                        title="Salvar"
                      />
                    </Grid>
                  </Grid>
                </Grid>
                <BoxVacancies
                  quantity={data && data.remaining}
                  title="Restante"
                  md={2}
                  sm={4}
                  xs={12}
                />
                <BoxVacancies
                  background="purple"
                  quantity={data && data.requested}
                  title="Realizadas"
                  md={2}
                  sm={4}
                  xs={12}
                />
                <BoxVacancies
                  background="pink"
                  quantity={data && data.confirmed}
                  title="Confirmadas"
                  md={2}
                  sm={4}
                  xs={12}
                />
              </Grid>
            </Form>
          );
        }}
      </Formik>
      <Grid
        className={classes.boxContentRegistration}
        container
        direction="row"
      >
        <TitleWithLine title="Matrículas" />
      </Grid>
      <Grid container direction="row" spacing={4}>
        {registrations()}
      </Grid>
    </>
  );
};

Create.propTypes = {
  vacancies: PropTypes.number,
  handleChange: PropTypes.func,
  handleSubmit: PropTypes.func,
  initialValues: PropTypes.object,
  isEdit: PropTypes.bool,
  data: PropTypes.object
};

export default Create;
