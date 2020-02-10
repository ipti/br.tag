import React from "react";
import Grid from "@material-ui/core/Grid";
import { makeStyles, withStyles } from "@material-ui/core/styles";
import TextField from "@material-ui/core/TextField";
import MaskedInput from "react-text-mask";
import { Formik, Form } from "formik";
import {
  FormLabel,
  FormControl,
  RadioGroup,
  Radio,
  FormControlLabel
} from "@material-ui/core";
import { ButtonPurple } from "../../components/Buttons";
import Header from "../../components/Layouts/Header";
import styleBase from "../../styles";
import styles from "./styles";
import * as Yup from "yup";

const useStyles = makeStyles(styles);

const PurpleRadio = withStyles({
  root: {
    "&$checked": {
      color: styleBase.colors.purple
    }
  },
  checked: {}
})(props => <Radio color="default" {...props} />);

const TextMaskFone = props => {
  const { ...other } = props;

  return (
    <MaskedInput
      {...other}
      mask={[
        "(",
        /[1-9]/,
        /\d/,
        ")",
        " ",
        /\d/,
        /\d/,
        /\d/,
        /\d/,
        /\d/,
        "-",
        /\d/,
        /\d/,
        /\d/,
        /\d/
      ]}
      placeholderChar={"\u2000"}
      showMask
    />
  );
};

const StepFour = props => {
  const classes = useStyles();

  const validationSchema = Yup.object().shape({
    responsableName: Yup.string().required("Campo é obrigatório!"),
    fone: Yup.string().required("Campo é obrigatório!"),
    residenceZone: Yup.string().required("Campo é obrigatório!")
  });

  const initialValues = {
    responsableName: props.student ? props.student.filiation1 : "",
    fone: "",
    residenceZone: props.student ? props.student.residenceZone : ""
  };

  return (
    <>
      <Header />
      <Formik
        initialValues={initialValues}
        onSubmit={values => props.next(5, values)}
        validationSchema={validationSchema}
        validateOnChange={false}
        enableReinitialize
      >
        {props => {
          return (
            <Form>
              <Grid
                className={`${classes.contentMain}`}
                container
                direction="row"
                justify="center"
                alignItems="center"
              >
                <Grid item md={3} sm={6} xs={10}>
                  <FormControl
                    component="fieldset"
                    className={classes.formControl}
                  >
                    <FormLabel>Responsável</FormLabel>
                    <TextField
                      name="responsableName"
                      value={props.values.responsableName}
                      onChange={props.handleChange}
                      variant="outlined"
                      className={classes.textField}
                    />

                    <div className={classes.formFieldError}>
                      {props.errors.responsableName}
                    </div>
                  </FormControl>
                </Grid>
              </Grid>
              <Grid
                className={`${classes.contentMain}`}
                container
                direction="row"
                justify="center"
                alignItems="center"
              >
                <Grid item md={3} sm={6} xs={10}>
                  <FormControl
                    component="fieldset"
                    className={classes.formControl}
                  >
                    <FormLabel>Telefone</FormLabel>
                    <TextField
                      name="fone"
                      variant="outlined"
                      className={classes.textField}
                      InputProps={{
                        inputComponent: TextMaskFone,
                        value: props.values.fone,
                        onChange: props.handleChange
                      }}
                    />
                    <div className={classes.formFieldError}>
                      {props.errors.fone}
                    </div>
                  </FormControl>
                </Grid>
              </Grid>
              <Grid
                className={`${classes.contentMain}`}
                container
                direction="row"
                justify="center"
                alignItems="center"
              >
                <Grid item md={3} sm={6} xs={10}>
                  <FormControl
                    component="fieldset"
                    className={classes.formControl}
                  >
                    <FormLabel component="legend">Zona</FormLabel>
                    <RadioGroup
                      value={props.values.residenceZone}
                      name="residenceZone"
                      onChange={props.handleChange}
                      row
                    >
                      <FormControlLabel
                        value="2"
                        control={<PurpleRadio />}
                        label="Urbana"
                      />
                      <FormControlLabel
                        value="1"
                        control={<PurpleRadio />}
                        label="Rural"
                      />
                    </RadioGroup>
                    <div className={classes.formFieldError}>
                      {props.errors.residenceZone}
                    </div>
                  </FormControl>
                </Grid>
              </Grid>
              <Grid
                className={`${classes.marginTop} ${classes.marginButtom}`}
                justify="center"
                alignItems="center"
                container
                direction="row"
              >
                <Grid item md={2} sm={4} xs={6}>
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

export default StepFour;
