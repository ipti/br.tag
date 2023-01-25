import React from "react";
import PropTypes from "prop-types";

// Material UI
import TextField from "@material-ui/core/TextField";
import Grid from "@material-ui/core/Grid";
import { makeStyles } from "@material-ui/core/styles";
import { FormLabel, FormControl } from "@material-ui/core";
import Alert from "@material-ui/lab/Alert";
import Loading from "../../components/Loading/CircularLoadingButtomActions";

// Third party
import { Formik, Form } from "formik";

// Components
import { TitleWithLine } from "../../components/Titles";
import { BoxVacancies } from "../../components/Boxes";
import { BoxRegistration } from "../../components/Boxes";
import { ButtonPurple } from "../../components/Buttons";
import List from "../../components/List";

// Styles
import styles from "./styles";

const useStyles = makeStyles(theme => styles);

const Create = props => {
  const classes = useStyles();

  const {
    initialValues,
    handleSubmit,
    validationSchema,
    data,
    baseLink,
    loadingIcon
  } = props;


  const registrations = () => {
    const registrationList = data?.student_pre_identification ?? [];


    return registrationList.map((registration, index) => {
      return (
        <BoxRegistration
          link={`${baseLink}/${registration.id}`}
          key={index}
          name={registration.name}
          sex={registration.sex}
          md={4}
          sm={4}
          unavailable={registration.unavailable}
        />
      );
    });
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
                      variant="outlined"
                      name="vacancies"
                      value={props.values.vacancies}
                      onChange={props.handleChange}
                      className={classes.textField}
                    />

                    <div className={classes.formFieldError}>
                      {props.errors.vacancies}
                    </div>
                  </FormControl>
                  <Grid container direction="row">
                    <Grid item md={4} sm={6}>
                      {!loadingIcon ? (
                        <ButtonPurple
                          onClick={props.handleSubmit}
                          type="submit"
                          title="Salvar"
                        />
                      ) : (
                        <Loading />
                      )}
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
        <List items={registrations()}>
          <Grid item xs={12}>
            <Alert variant="outlined" severity="warning">
              A turma não possui matrículas
            </Alert>
          </Grid>
        </List>
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
