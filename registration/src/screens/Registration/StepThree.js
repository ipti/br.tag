import React from "react";
import Grid from "@material-ui/core/Grid";
import { makeStyles, withStyles } from "@material-ui/core/styles";
import MaskedInput from "react-text-mask";
import TextField from "@material-ui/core/TextField";
import { Formik, Form } from "formik";
import * as Yup from "yup";
import {
  FormLabel,
  FormControl,
  Select,
  RadioGroup,
  Radio,
  FormControlLabel,
  MenuItem
} from "@material-ui/core";
import { ButtonPurple } from "../../components/Buttons";
import Header from "../../components/Layouts/Header";
import styleBase from "../../styles";
import styles from "./styles";

const useStyles = makeStyles(styles);

const PurpleRadio = withStyles({
  root: {
    "&$checked": {
      color: styleBase.colors.purple
    }
  },
  checked: {}
})(props => <Radio color="default" {...props} />);

const TextMaskDate = props => {
  const { ...other } = props;

  return (
    <MaskedInput
      {...other}
      mask={[/\d/, /\d/, "/", /\d/, /\d/, "/", /\d/, /\d/, /\d/, /\d/]}
      placeholderChar={"_"}
      showMask
    />
  );
};

const StepThree = props => {
  const classes = useStyles();

  const validationSchema = Yup.object().shape({
    studentName: Yup.string().required("Campo é obrigatório!"),
    birthday: Yup.string().required("Campo é obrigatório!"),
    sex: Yup.string().required("Campo é obrigatório!"),
    colorRace: Yup.string().required("Campo é obrigatório!")
  });

  const initialValues = {
    studentName: props.student ? props.student.name : "",
    birthday: props.student ? props.student.birthday : "",
    colorRace: props.student ? props.student.colorRace : "0",
    sex: props.student ? props.student.sex : ""
  };

  return (
    <>
      <Header />
      <Formik
        initialValues={initialValues}
        onSubmit={values => props.next(4, values)}
        validationSchema={validationSchema}
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
                <Grid item md={3} sm={6} xs={10}>
                  <FormControl
                    component="fieldset"
                    className={classes.formControl}
                  >
                    <FormLabel>Aluno</FormLabel>
                    <TextField
                      name="studentName"
                      onChange={props.handleChange}
                      value={props.values.studentName}
                      variant="outlined"
                      className={classes.textField}
                    />

                    <div className={classes.formFieldError}>
                      {props.errors.studentName}
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
                    <FormLabel>Nascimento</FormLabel>
                    <TextField
                      name="birthday"
                      variant="outlined"
                      className={classes.textField}
                      InputProps={{
                        inputComponent: TextMaskDate,
                        value: props.values.birthday,
                        onChange: props.handleChange
                      }}
                    />
                    <div className={classes.formFieldError}>
                      {props.errors.birthday}
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
                    <FormLabel>Cor/Raça</FormLabel>
                    <Select
                      variant="outlined"
                      name="colorRace"
                      value={props.values.colorRace}
                      onChange={props.handleChange}
                    >
                      <MenuItem value={`0`}>Não Declarada</MenuItem>
                      <MenuItem value={`1`}>Branca</MenuItem>
                      <MenuItem value={`2`}>Preta</MenuItem>
                      <MenuItem value={`3`}>Parda</MenuItem>
                      <MenuItem value={`4`}>Amarela</MenuItem>
                      <MenuItem value={`5`}>Indígena</MenuItem>
                    </Select>

                    <div className={classes.formFieldError}>
                      {props.errors.colorRace}
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
                    <FormLabel component="legend">Sexo</FormLabel>

                    <RadioGroup
                      value={props.values.sex}
                      name="sex"
                      onChange={props.handleChange}
                      row
                    >
                      <FormControlLabel
                        value="2"
                        name="sex"
                        control={<PurpleRadio />}
                        label="Feminino"
                      />
                      <FormControlLabel
                        value="1"
                        name="sex"
                        control={<PurpleRadio />}
                        label="Masculino"
                      />
                    </RadioGroup>
                    <div className={classes.formFieldError}>
                      {props.errors.sex}
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

export default StepThree;
