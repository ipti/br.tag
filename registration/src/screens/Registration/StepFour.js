import React from "react";

// Material UI
import {
  FormLabel,
  FormControl,
  FormHelperText,
  TextField,
  Grid
} from "@material-ui/core";

import { makeStyles } from "@material-ui/core/styles";

// Components
import { ButtonPurple } from "../../components/Buttons";

// Third party
import * as Yup from "yup";
import MaskedInput from "react-text-mask";
import { Formik, Form } from "formik";

// Styles
import styleBase from "../../styles";
import styles from "./styles";

const useStyles = makeStyles(styles);

const TextMaskFone = props => {
  const { inputRef, ...other } = props;

  return (
    <MaskedInput
      {...other}
      ref={ref => {
        inputRef(ref ? ref.inputElement : null);
      }}
      mask={[ "(", /[1-9]/, /\d/, ")", " ", /\d/, /\d/, /\d/, /\d/, /\d/, "-", /\d/, /\d/, /\d/, /\d/ ]}
      placeholderChar={"\u2000"}
      showMask
    />
  );
};

const TextMaskCpf = props => {
  const { inputRef, ...others } = props;

  return (
    <MaskedInput
      {...others}
      ref={ref => {
        inputRef(ref ? ref.inputElement : null);
      }}
      mask={[/\d/, /\d/, /\d/, ".", /\d/, /\d/, /\d/, ".", /\d/, /\d/, /\d/, "-", /\d/, /\d/]}
      placeholderChar={"_"}
      showMask
    />
  );
};

const StepFour = props => {
  const classes = useStyles();

  const validationSchema = Yup.object().shape({
    responsableName: Yup.string().required("Campo obrigatório!"),
    responsableCpf: Yup.string().required("Campo obrigatório!"),
    fone: Yup.string().required("Campo obrigatório!")
  });

  const initialValues = {
    motherName: '' ??  '',
    fatherName: '' ?? '',
    responsableName: props?.student?.filiation1 ?? '',
    fone: "",
    responsableCpf: ''
  };

  return (
    <>
      <Formik
        initialValues={initialValues}
        onSubmit={values => props.next(5, values)}
        validationSchema={validationSchema}
        validateOnChange={false}
        enableReinitialize
      >
        {({ errors, values, touched, handleChange, handleSubmit }) => {

          const errorList = {
            responsableName: touched.responsableName && errors.responsableName,
            responsableCpf: touched.responsableCpf && errors.responsableCpf,
            fone: touched.fone && errors.fone,
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
                      >
                        <FormLabel>Nome Completo da Mãe *</FormLabel>
                        <TextField
                          name="motherName"
                          value={values.motherName}
                          onChange={handleChange}
                          variant="outlined"
                          className={classes.textField}
                        />
                      </FormControl>
                  </Grid>
              </Grid>
              <Grid 
                className={`${classes.contentMain}`}
                container
                direction="row"
                justifyContent="center"
                alignItems="center"
              >
                  <Grid item xs={12}>
                      <FormControl
                        component="fieldset"
                        className={classes.formControl}  
                      >
                        <FormLabel>Nome Completo do Pai</FormLabel>
                        <TextField
                        name="fatherName"
                        value={values.fatherName}
                        onChange={handleChange}
                        variant="outlined"
                        className={classes.textField}
                      />
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
                    error={errorList.responsableName}
                  >
                    <FormLabel>Nome Completo do Responsável *</FormLabel>
                    <TextField
                      name="responsableName"
                      value={values.responsableName}
                      onChange={handleChange}
                      variant="outlined"
                      className={classes.textField}
                      error={errorList.responsableName}
                    />
                    <FormHelperText>{errorList.responsableName}</FormHelperText>               
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
                    error={errorList.studentName}
                  >
                    <FormLabel>Nº do CPF do Responsável *</FormLabel>
                    <TextField
                      name="responsableCpf"
                      variant="outlined"
                      InputProps={{
                        inputComponent: TextMaskCpf,
                        value: values.responsableCpf,
                        onChange: handleChange
                      }}
                      className={classes.textField}
                      error={errorList.responsableCpf}
                      autoComplete="off"
                    />
                    <FormHelperText>{errorList.responsableCpf}</FormHelperText>
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
                    error={errorList.fone}
                  >
                    <FormLabel>Telefone *</FormLabel>
                    <TextField
                      name="fone"
                      variant="outlined"
                      className={classes.textField}
                      InputProps={{
                        inputComponent: TextMaskFone,
                        value: values.fone,
                        onChange: handleChange
                      }}
                      error={errorList.fone}
                    />
                    <FormHelperText>{errorList.fone}</FormHelperText>
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

export default StepFour;
