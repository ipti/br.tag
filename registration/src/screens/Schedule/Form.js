import React from "react";
import PropTypes from "prop-types";
import TextField from "@material-ui/core/TextField";
import Grid from "@material-ui/core/Grid";
import Loading from "../../components/Loading/CircularLoadingButtomActions";
import { Formik, Form } from "formik";
import {
  FormControlLabel,
  FormLabel,
  FormControl,
  Switch
} from "@material-ui/core";
import { ButtonPurple } from "../../components/Buttons";
import brLocale from "date-fns/locale/pt-BR";
import {
  MuiPickersUtilsProvider,
  KeyboardDatePicker
} from "@material-ui/pickers";
import DateFnsUtils from "@date-io/date-fns";
import { TitleWithLine } from "../../components/Titles";
import {
  makeStyles,
  createMuiTheme,
  ThemeProvider
} from "@material-ui/core/styles";
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

const Create = props => {
  const classes = useStyles();
  const {
    handleChangeActive,
    initialValues,
    handleSubmit,
    validationSchema,
    isEdit,
    loadingIcon
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
            {`Cronograma - ${isEdit ? "Editar" : "Adicionar"}`}
          </h1>
        </Grid>
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
              <MuiPickersUtilsProvider locale={brLocale} utils={DateFnsUtils}>
                <Grid item md={12} sm={12}>
                  <Grid
                    container
                    direction="row"
                    alignItems="center"
                    spacing={2}
                  >
                    <Grid item md={3} sm={3}>
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
                          className={classes.textField}
                        />

                        <div className={classes.formFieldError}>
                          {props.errors.year}
                        </div>
                      </FormControl>
                    </Grid>
                    <Grid item md={3} sm={3}>
                      <ThemeProvider theme={theme}>
                        <FormControlLabel
                          control={
                            <Switch
                              checked={props.values.isActive || false}
                              name="isActive"
                              onChange={handleChangeActive}
                              color="primary"
                              inputProps={{ "aria-label": "primary checkbox" }}
                              label="Ativo"
                            />
                          }
                          label="Ativo"
                        />
                      </ThemeProvider>
                    </Grid>
                  </Grid>
                </Grid>
                <Grid
                  className={classes.marginButtom}
                  container
                  direction="row"
                  spacing={2}
                >
                  <Grid item md={12} sm={12}>
                    <TitleWithLine title="Transferência Interna" />
                  </Grid>
                  <Grid item md={4} sm={4}>
                    <FormControl
                      component="fieldset"
                      className={classes.formControl}
                    >
                      <FormLabel>Data Início</FormLabel>
                      <KeyboardDatePicker
                        disableToolbar
                        name="internalTransferDateStart"
                        inputVariant="outlined"
                        format="dd/MM/yyyy"
                        margin="normal"
                        value={props.values.internalTransferDateStart}
                        onChange={value =>
                          props.setFieldValue(
                            "internalTransferDateStart",
                            value
                          )
                        }
                        KeyboardButtonProps={{
                          "aria-label": "Alterar data"
                        }}
                      />

                      <div className={classes.formFieldError}>
                        {props.errors.internalTransferDateStart}
                      </div>
                    </FormControl>
                  </Grid>
                  <Grid item md={4} sm={4}>
                    <FormControl
                      component="fieldset"
                      className={classes.formControl}
                    >
                      <FormLabel>Data Fim</FormLabel>
                      <KeyboardDatePicker
                        disableToolbar
                        name="internalTransferDateEnd"
                        value={props.values.internalTransferDateEnd}
                        inputVariant="outlined"
                        format="dd/MM/yyyy"
                        margin="normal"
                        onChange={value =>
                          props.setFieldValue("internalTransferDateEnd", value)
                        }
                        KeyboardButtonProps={{
                          "aria-label": "Alterar data"
                        }}
                      />

                      <div className={classes.formFieldError}>
                        {props.errors.internalTransferDateEnd}
                      </div>
                    </FormControl>
                  </Grid>
                </Grid>
                <Grid
                  className={classes.marginButtom}
                  container
                  direction="row"
                  spacing={2}
                >
                  <Grid item md={12} sm={12}>
                    <TitleWithLine title="Novos Alunos" />
                  </Grid>
                  <Grid item md={4} sm={4}>
                    <FormControl
                      component="fieldset"
                      className={classes.formControl}
                    >
                      <FormLabel>Data Início</FormLabel>
                      <KeyboardDatePicker
                        disableToolbar
                        name="newStudentDateStart"
                        value={props.values.newStudentDateStart}
                        inputVariant="outlined"
                        format="dd/MM/yyyy"
                        margin="normal"
                        onChange={value =>
                          props.setFieldValue("newStudentDateStart", value)
                        }
                        KeyboardButtonProps={{
                          "aria-label": "Alterar data"
                        }}
                      />

                      <div className={classes.formFieldError}>
                        {props.errors.newStudentDateStart}
                      </div>
                    </FormControl>
                  </Grid>
                  <Grid item md={4} sm={4}>
                    <FormControl
                      component="fieldset"
                      className={classes.formControl}
                    >
                      <FormLabel>Data Fim</FormLabel>
                      <KeyboardDatePicker
                        disableToolbar
                        name="newStudentDateEnd"
                        value={props.values.newStudentDateEnd}
                        inputVariant="outlined"
                        format="dd/MM/yyyy"
                        margin="normal"
                        onChange={value =>
                          props.setFieldValue("newStudentDateEnd", value)
                        }
                        KeyboardButtonProps={{
                          "aria-label": "Alterar data"
                        }}
                      />

                      <div className={classes.formFieldError}>
                        {props.errors.newStudentDateEnd}
                      </div>
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
            </Form>
          );
        }}
      </Formik>
    </>
  );
};

Create.propTypes = {
  year: PropTypes.string
};

export default Create;
