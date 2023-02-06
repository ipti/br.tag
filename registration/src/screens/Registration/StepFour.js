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
    responsable_name: Yup.string().required("Campo obrigatório!"),
    responsable_cpf: Yup.string().required("Campo obrigatório!"),
    responsable_telephone: Yup.string().required("Campo obrigatório!")
  });

  const initialValues = {
    mother_name: props?.student?.mother_name ??  '',
    father_name: props?.student?.father_name ?? '',
    responsable_name: props?.student?.responsable_name ?? '',
    responsable_telephone: props?.student?.responsable_telephone ?? "",
    responsable_cpf: props?.student?.responsable_cpf ?? ''
  };

  return (
    <>
      <Formik
        initialValues={initialValues}
        onSubmit={values => props.next(6, values)}
        validationSchema={validationSchema}
        validateOnChange={false}
        enableReinitialize
      >
        {({ errors, values, touched, handleChange, handleSubmit }) => {

          const errorList = {
            responsable_name: touched.responsable_name && errors.responsable_name,
            responsable_cpf: touched.responsable_cpf && errors.responsable_cpf,
            responsable_telephone: touched.responsable_telephone && errors.responsable_telephone,
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
                        <FormLabel>Nome Completo da Mãe</FormLabel>
                        <TextField
                          name="mother_name"
                          value={values.mother_name}
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
                        name="father_name"
                        value={values.father_name}
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
                    error={errorList.responsable_name}
                  >
                    <FormLabel>Nome Completo do Responsável *</FormLabel>
                    <TextField
                      name="responsable_name"
                      value={values.responsable_name}
                      onChange={handleChange}
                      variant="outlined"
                      className={classes.textField}
                      error={errorList.responsable_name}
                    />
                    <FormHelperText>{errorList.responsable_name}</FormHelperText>               
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
                    error={errorList.responsable_cpf}
                  >
                    <FormLabel>Nº do CPF do Responsável *</FormLabel>
                    <TextField
                      name="responsable_cpf"
                      variant="outlined"
                      InputProps={{
                        inputComponent: TextMaskCpf,
                        value: values.responsable_cpf,
                        onChange: handleChange
                      }}
                      className={classes.textField}
                      error={errorList.responsable_cpf}
                      autoComplete="off"
                    />
                    <FormHelperText>{errorList.responsable_cpf}</FormHelperText>
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
                    error={errorList.responsable_telephone}
                  >
                    <FormLabel>Telefone *</FormLabel>
                    <TextField
                      name="responsable_telephone"
                      variant="outlined"
                      className={classes.textField}
                      InputProps={{
                        inputComponent: TextMaskFone,
                        value: values.responsable_telephone,
                        onChange: handleChange
                      }}
                      error={errorList.responsable_telephone}
                    />
                    <FormHelperText>{errorList.responsable_telephone}</FormHelperText>
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
