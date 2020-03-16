import React from "react";

// Material UI
import {
  Grid,
  FormLabel,
  FormControl,
  Select,
  RadioGroup,
  Radio,
  FormControlLabel,
  MenuItem,
  FormHelperText,
  TextField
} from "@material-ui/core";

import { makeStyles, withStyles } from "@material-ui/core/styles";

// Components
import { ButtonPurple } from "../../components/Buttons";

// Third party
import MaskedInput from "react-text-mask";
import { Formik, Form } from "formik";
import * as Yup from "yup";

// Styles
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
  const { inputRef, ...others } = props;

  return (
    <MaskedInput
      {...others}
      ref={ref => {
        inputRef(ref ? ref.inputElement : null);
      }}
      mask={[/\d/, /\d/, "/", /\d/, /\d/, "/", /\d/, /\d/, /\d/, /\d/]}
      placeholderChar={"_"}
      showMask
    />
  );
};

const StepThree = props => {
  const classes = useStyles();

  const validationSchema = Yup.object().shape({
    studentName: Yup.string().required("Campo obrigatório!"),
    birthday: Yup.string().required("Campo obrigatório!"),
    sex: Yup.string().required("Campo obrigatório!"),
    colorRace: Yup.string().required("Campo obrigatório!")
  });

  const initialValues = {
    studentName: props?.student?.name ?? '',
    birthday: props?.student?.birthday ?? '',
    colorRace: props?.student?.colorRace ?? '',
    sex: props?.student?.sex ?? ""
  };

  return (
    <>
      <Formik
        initialValues={initialValues}
        onSubmit={values => props.next(4, values)}
        validationSchema={validationSchema}
        validateOnChange={false}
        enableReinitialize
      >
        {({ errors, values, touched, handleChange, handleSubmit }) => {

          const errorList = {
            studentName: touched.studentName && errors.studentName,
            birthday: touched.birthday && errors.birthday,
            colorRace: touched.colorRace && errors.colorRace,
            sex: touched.sex && errors.sex,
          };

          return (
            <Form>
              <Grid
                className={`${classes.marginTop} ${classes.contentMain}`}
                container
                direction="row"
                justify="center"
                alignItems="center"
              >
                <Grid item xs={12}>
                  <FormControl
                    component="fieldset"
                    className={classes.formControl}
                    error={errorList.studentName}
                  >
                    <FormLabel>Aluno</FormLabel>
                    <TextField
                      name="studentName"
                      onChange={handleChange}
                      value={values.studentName}
                      variant="outlined"
                      className={classes.textField}
                      error={errorList.studentName}
                      autoComplete="off"
                    />
                    <FormHelperText>{errorList.studentName}</FormHelperText>
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
                <Grid item xs={12}>
                  <FormControl
                    component="fieldset"
                    className={classes.formControl}
                    error={errorList.birthday}
                  >
                    <FormLabel>Nascimento</FormLabel>
                    <TextField
                      name="birthday"
                      variant="outlined"
                      className={classes.textField}
                      InputProps={{
                        inputComponent: TextMaskDate,
                        value: values.birthday,
                        onChange: handleChange
                      }}
                      error={errorList.birthday}
                    />
                    <FormHelperText>{errorList.colorRace}</FormHelperText>
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
                <Grid item xs={12}>
                  <FormControl
                    component="fieldset"
                    className={classes.formControl}
                    error={errorList.colorRace}
                  >
                    <FormLabel>Cor/Raça</FormLabel>
                    <Select
                      variant="outlined"
                      name="colorRace"
                      value={values.colorRace}
                      onChange={handleChange}
                    >
                      <MenuItem value={`0`}>Não Declarada</MenuItem>
                      <MenuItem value={`1`}>Branca</MenuItem>
                      <MenuItem value={`2`}>Preta</MenuItem>
                      <MenuItem value={`3`}>Parda</MenuItem>
                      <MenuItem value={`4`}>Amarela</MenuItem>
                      <MenuItem value={`5`}>Indígena</MenuItem>
                    </Select>
                    <FormHelperText>{errorList.colorRace}</FormHelperText>
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
                <Grid item xs={12}>
                  <FormControl
                    component="fieldset"
                    className={classes.formControl}
                    error={errorList.sex}
                  >
                    <FormLabel component="legend">Sexo</FormLabel>
                    <RadioGroup
                      value={values.sex}
                      name="sex"
                      onChange={handleChange}
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
                    <FormHelperText>{errorList.sex}</FormHelperText>
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
                <Grid item xs={6}>
                  <ButtonPurple
                    onClick={handleSubmit}
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
