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


  console.log(data)
  const registrations = () => {
    const registrationList = data?.student_pre_identification ?? [];


    return registrationList.map((registration, index) => {
      return (
        <BoxRegistration
          link={`${baseLink}/${registration?.id}`}
          key={index}
          name={registration?.name}
          sex={registration?.sex}
          student_fk={registration?.student_fk}
          md={4}
          sm={4}
          unavailable={registration?.unavailable}
        />
      );
    });
  };

  return (
    <>
      <Grid container direction="row">
        <TitleWithLine title={data && data.edcenso_stage_vs_modality.name} />
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

                <BoxVacancies
                  quantity={data && (data.vacancy - data.student_pre_identification.length)}
                  title="Restante"
                  md={2}
                  sm={4}
                  xs={12}
                />
                {/* <BoxVacancies
                  background="purple"
                  quantity={data && data.requested}
                  title="Realizadas"
                  md={2}
                  sm={4}
                  xs={12}
                /> */}
                <BoxVacancies
                  background="pink"
                  quantity={data && data.student_pre_identification.length}
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
