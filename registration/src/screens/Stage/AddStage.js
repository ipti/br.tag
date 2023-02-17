import DateFnsUtils from "@date-io/date-fns";
import {
  FormControl, FormLabel
} from "@material-ui/core";
import Grid from "@material-ui/core/Grid";
import {
  createMuiTheme, makeStyles
} from "@material-ui/core/styles";
import TextField from "@material-ui/core/TextField";
import {
  KeyboardDatePicker, MuiPickersUtilsProvider
} from "@material-ui/pickers";
import brLocale from "date-fns/locale/pt-BR";
import { Form, Formik } from "formik";
import PropTypes from "prop-types";
import React from "react";
import Select from 'react-select';
import { ButtonPurple } from "../../components/Buttons";
import Loading from "../../components/Loading/CircularLoadingButtomActions";
import { TitleWithLine } from "../../components/Titles";
import styleBase from "../../styles";
import styles from "./styles";

const useStyles = makeStyles(theme => styles);

const theme = createMuiTheme({
  palette: {
    primary: {
      main: styleBase.colors.purple
    }
  }
});

const customStyles = {
  control: base => ({
    ...base,
    height: "60px",

    minHeight: "60px",
    fontFamily: "Roboto, Helvetica, Arial, sans-serif"
  }),
  menu: base => ({
    ...base,
    fontFamily: "Roboto, Helvetica, Arial, sans-serif"
  })
};

const Create = props => {
  const classes = useStyles();
  const {
    handleChangeActive,
    initialValues,
    handleSubmit,
    validationSchema,
    isEdit,
    loadingIcon,
    stages
  } = props;



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
            {`Adicionar Ano Escolar`}
          </h1>

        </Grid>
      </Grid>
      <Formik
        initialValues={initialValues}
        onSubmit={handleSubmit}
       // validationSchema={validationSchema}
        validateOnChange={false}
        enableReinitialize
      >
        {props => {
          console.log(props)
          return (
            <Form>
              <MuiPickersUtilsProvider locale={brLocale} utils={DateFnsUtils}>
                <Grid item md={12} sm={12}>
                  <Grid
                    container
                    direction="row"
                    alignItems="center"
                    spacing={2}
                  >
                    <Grid item md={12} sm={12}>
                      <TitleWithLine title="Vagas" />
                    </Grid>
                    <Grid item md={5} sm={5}>
                      <FormControl
                        component="fieldset"
                        className={classes.formControl}
                      >
                        <FormLabel>Vagas</FormLabel>
                        <TextField
                          name="vacancy"
                          value={props.values.vacancy}
                          onChange={props.handleChange}
                          id="outlined-size-small"
                          variant="outlined"
                          required
                          className={classes.textField}
                        />
                        <div className={classes.formFieldError}>
                          {props.errors.vacancy}
                        </div>
                      </FormControl>
                    </Grid>
                  </Grid>
                </Grid>
                <Grid item md={12} sm={12}>
                  <Grid
                    container
                    direction="column"
                    //alignItems="center"
                    spacing={2}
                  >

                    <Grid item md={12} sm={12}>
                      <TitleWithLine title="Ano" />
                    </Grid>
                    <Grid item md={5} sm={5}>
                      <FormControl
                        component="fieldset"
                        className={classes.formControl}
                      >
                        <FormLabel>Ano</FormLabel>
                        <TextField
                          name="year"
                          value={props.values.year}
                          onChange={props.handleChange}
                          id="outlined-size-small"
                          variant="outlined"
                          required
                          className={classes.textField}
                        />

                        <div className={classes.formFieldError}>
                          {props.errors.year}
                        </div>
                      </FormControl>
                    </Grid>
                  </Grid>
                </Grid>
                <Grid
                  className={classes.marginButtom}
                  container
                  direction="column"
                  spacing={2}
                >
                  <Grid item md={12} sm={12}>
                    <TitleWithLine title="Ano Escolar" />
                  </Grid>
                  <Grid item md={5} sm={5}>
                    <FormControl
                      component="fieldset"
                      className={classes.formControl}
                    >
                      <Select
                        getOptionValue={opt => opt.name}
                        getOptionLabel={opt => opt.name}
                        onChange={selectedOption => {
                          props.setFieldValue("edcenso_stage_vs_modality", selectedOption.id)
                        }}
                        styles={customStyles}
                        name="edcenso_stage_vs_modality"
                        options={stages}
                        className="basic-multi-select"
                        classNamePrefix="select"
                      />
                    </FormControl>
                  </Grid>
                </Grid>
                <Grid
                  className={classes.marginButtom}
                  container
                  direction="row"
                >
                  <Grid item md={2} sm={2}>
                    {!loadingIcon ? (
                      <ButtonPurple
                        onClick={props.handleSubmit}
                        type="submit"
                        title={isEdit ? "Editar" : "Salvar"}
                      />
                    ) : (
                      <Loading />
                    )}
                  </Grid>
                </Grid>
              </MuiPickersUtilsProvider>
            </Form >
          );
        }}
      </Formik >
    </>
  );
};

Create.propTypes = {
  year: PropTypes.string
};

export default Create;
