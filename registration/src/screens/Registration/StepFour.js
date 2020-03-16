import React from "react";

// Material UI
import {
  FormLabel,
  FormControl,
  RadioGroup,
  Radio,
  FormControlLabel,
  FormHelperText,
  TextField,
  Grid
} from "@material-ui/core";

import { makeStyles, withStyles } from "@material-ui/core/styles";

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

const PurpleRadio = withStyles({
  root: {
    "&$checked": {
      color: styleBase.colors.purple
    }
  },
  checked: {}
})(props => <Radio color="default" {...props} />);

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

const StepFour = props => {
  const classes = useStyles();

  const validationSchema = Yup.object().shape({
    responsableName: Yup.string().required("Campo obrigatório!"),
    fone: Yup.string().required("Campo obrigatório!"),
    residenceZone: Yup.string().required("Campo obrigatório!")
  });

  const initialValues = {
    responsableName: props?.student?.filiation1 ?? '',
    fone: "",
    residenceZone: props?.student?.residenceZone ?? ''
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
            residenceZone: touched.residenceZone && errors.residenceZone,
            fone: touched.fone && errors.fone,
          };

          return (
            <Form>
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
                    <FormLabel>Responsável</FormLabel>
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
                    error={errorList.fone}
                  >
                    <FormLabel>Telefone</FormLabel>
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
                    error={errorList.residenceZone}
                  >
                    <FormLabel component="legend">Zona</FormLabel>
                    <RadioGroup
                      value={values.residenceZone}
                      name="residenceZone"
                      onChange={handleChange}
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
                    <FormHelperText>{errorList.residenceZone}</FormHelperText>
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
