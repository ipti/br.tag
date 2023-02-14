import React, { useContext } from "react";
import RegistrationContext from '../../containers/Registration/context';

// Material UI
import {
  Checkbox, FormControl, FormControlLabel, FormGroup, FormHelperText, FormLabel, Grid, MenuItem, Radio, RadioGroup, Select, TextField
} from "@material-ui/core";

import { makeStyles, withStyles } from "@material-ui/core/styles";

// Components
import { ButtonPurple } from "../../components/Buttons";

// Third party
import { Form, Formik } from "formik";
import MaskedInput from "react-text-mask";
import * as Yup from "yup";

// Styles
import { useState } from "react";
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

const TextMaskCpf = props => {
  const { inputRef, ...others } = props;

  return (
    <MaskedInput
      {...others}
      ref={ref => {
        inputRef(ref ? ref.inputElement : null);
      }}
      mask={[/\d/, /\d/, /\d/, ".", /\d/, /\d/, /\d/, ".", /\d/, /\d/, /\d/, "-", /\d/, /\d/]}
      // placeholderChar={"_"}
      showMask
    />
  );
};

const checkDeficiency = (deficiency, setFieldValue) => {
  if (deficiency) {
    setFieldValue("deficiency_type_blindness", false);
    setFieldValue("deficiency_type_low_vision", false);
    setFieldValue("deficiency_type_deafness", false);
    setFieldValue("deficiency_type_disability_hearing", false);
    setFieldValue("deficiency_type_deafblindness", false);
    setFieldValue("deficiency_type_phisical_disability", false);
    setFieldValue("deficiency_type_intelectual_disability", false);
    setFieldValue("deficiency_type_multiple_disabilities", false);
    setFieldValue("deficiency_type_autism", false);
    setFieldValue("deficiency_type_gifted", false);
  }
}

const StepThree = props => {
  const classes = useStyles();
  const [cegueiraDisabled, setCegueiraDisabled] = useState(false);
  const [baixaVisaoDisabled, setBaixaVisaoDisabled] = useState(false);
  const [surdezDisabled, setSurdezDisabled] = useState(false);
  const [defAuditivaDisabled, setDefAuditivaDisabled] = useState(false);
  const [surdoCegueiraDisabled, setSurdoCegueiraDisabled] = useState(false);
  const [defFisicaDisabled] = useState(false);
  const [defIntelectualDisabled, setDefIntelectualDisabled] = useState(false);
  const [deficienciaMultDisabled] = useState(false);
  const [transAutistaDisabled] = useState(false);
  const [superDotacaoDisabled, setSuperDotacaoDisabled] = useState(false);

  const validationSchema = Yup.object().shape({
    name: Yup.string().required("Campo obrigatório!"),
    birthday: Yup.string().required("Campo obrigatório!"),
    sex: Yup.number().required("Campo obrigatório!"),
    color_race: Yup.number().required("Campo obrigatório!"),
    cpf: Yup.string().required("Campo obrigatório!"),
    deficiency: Yup.boolean().required("Campo obrigatório!"),
  });

  const { idSchool, idStage, idStagevsmodality, idEvent } = useContext(RegistrationContext);
  console.log(idStage)
  const initialValues = {
    school_identification: idSchool,
    event_pre_registration: idEvent,
    edcenso_stage_vs_modality: idStagevsmodality,
    stages_vacancy_pre_registration: idStage,
    name: props?.student?.name ?? '',
    birthday: props?.student?.birthday ?? '',
    color_race: props?.student?.color_race ?? '',
    sex: props?.student?.sex ?? '',
    cpf: props?.student?.cpf ?? "",
    deficiency: props?.student?.deficiency ?? false,
    deficiency_type_blindness: props?.student?.deficiency_type_blindness ?? false,
    deficiency_type_low_vision: props?.student?.deficiency_type_low_vision ?? false,
    deficiency_type_deafness: props?.student?.deficiency_type_deafness ?? false,
    deficiency_type_disability_hearing: props?.student?.deficiency_type_disability_hearing ?? false,
    deficiency_type_deafblindness: props?.student?.deficiency_type_deafblindness ?? false,
    deficiency_type_phisical_disability: props?.student?.deficiency_type_phisical_disability ?? false,
    deficiency_type_intelectual_disability: props?.student?.deficiency_type_intelectual_disability ?? false,
    deficiency_type_multiple_disabilities: props?.student?.deficiency_type_multiple_disabilities ?? false,
    deficiency_type_autism: props?.student?.deficiency_type_autism ?? false,
    deficiency_type_gifted: props?.student?.deficiency_type_gifted ?? false
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
        {({ errors, values, touched, handleChange, handleSubmit, setFieldValue }) => {


          const errorList = {
            name: touched.name && errors.name,
            birthday: touched.birthday && errors.birthday,
            color_race: touched.color_race && errors.color_race,
            sex: touched.sex && errors.sex,
            cpf: touched.cpf && errors.cpf,
            deficiency: touched.deficiency && errors.deficiency
          };

          if (values.deficiency_type_blindness) {
            setBaixaVisaoDisabled(true);
            setSurdezDisabled(true);
            setSurdoCegueiraDisabled(true);
          } else {
            setBaixaVisaoDisabled(false);
            setSurdezDisabled(false);
          }

          if (values.deficiency_type_low_vision) {
            setCegueiraDisabled(true);
            setSurdoCegueiraDisabled(true);
          }
          if (!values.deficiency_type_low_vision && !values.deficiency_type_blindness) {
            setCegueiraDisabled(false);
            setSurdoCegueiraDisabled(false);
          }

          if (values.deficiency_type_deafness) {
            setCegueiraDisabled(true);
            setSurdoCegueiraDisabled(true);
            setDefAuditivaDisabled(true)
          } else {
            setDefAuditivaDisabled(false)
          }

          if (values.deficiency_type_disability_hearing) {
            setSurdoCegueiraDisabled(true);
            setSurdezDisabled(true);
          }

          if (values.deficiency_type_deafblindness) {
            setCegueiraDisabled(true);
            setBaixaVisaoDisabled(true);
            setSurdezDisabled(true);
            setDefAuditivaDisabled(true);
          }

          if (values.deficiency_type_intelectual_disability) {
            setSuperDotacaoDisabled(true)
          } else {
            setSuperDotacaoDisabled(false)
          }

          if (values.deficiency_type_gifted) {
            setDefIntelectualDisabled(true)
          } else {
            setDefIntelectualDisabled(false)
          }

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
                    error={errorList.name}
                  >
                    <FormLabel>Aluno *</FormLabel>
                    <TextField
                      name="name"
                      onChange={handleChange}
                      value={values.name}
                      variant="outlined"
                      className={classes.textField}
                      error={!!errorList.name}
                      autoComplete="off"
                    />
                    <FormHelperText>{errorList.name}</FormHelperText>
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
                    error={!!errorList.birthday}
                  >
                    <FormLabel>Nascimento *</FormLabel>
                    <TextField
                      name="birthday"
                      variant="outlined"
                      className={classes.textField}
                      InputProps={{
                        inputComponent: TextMaskDate,
                        value: values.birthday,
                        onChange: handleChange
                      }}
                      error={!!errorList.birthday}
                    />
                    <FormHelperText>{errorList.birthday}</FormHelperText>
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
                    <FormLabel>Nº do CPF</FormLabel>
                    <TextField
                      name="cpf"
                      variant="outlined"
                      InputProps={{
                        inputComponent: TextMaskCpf,
                        value: values.cpf,
                        onChange: handleChange
                      }}
                      className={classes.textField}
                      autoComplete="off"
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
                    error={errorList.color_race}
                  >
                    <FormLabel>Cor/Raça *</FormLabel>
                    <Select
                      variant="outlined"
                      name="color_race"
                      value={values.color_race}
                      onChange={handleChange}
                    >
                      <MenuItem value={0}>Não Declarada</MenuItem>
                      <MenuItem value={1}>Branca</MenuItem>
                      <MenuItem value={2}>Preta</MenuItem>
                      <MenuItem value={3}>Parda</MenuItem>
                      <MenuItem value={4}>Amarela</MenuItem>
                      <MenuItem value={5}>Indígena</MenuItem>
                    </Select>
                    <FormHelperText>{errorList.color_race}</FormHelperText>
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
                    error={errorList.sex}
                  >
                    <FormLabel component="legend">Sexo *</FormLabel>
                    <RadioGroup
                      value={values.sex}
                      name="sex"
                      onChange={handleChange}
                      row
                    >
                      <FormControlLabel
                        value={'2'}
                        name="sex"
                        control={<PurpleRadio />}
                        label="Feminino"
                      />
                      <FormControlLabel
                        value={'1'}
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
                    error={errorList.deficiency}
                  >
                    <FormLabel component="legend">Possui Deficiência? *</FormLabel>
                    <RadioGroup
                      value={values.deficiency}
                      name="deficiency"
                      onChange={handleChange}
                      onClick={() => checkDeficiency(values.deficiency, setFieldValue)}
                      row
                    >
                      <FormControlLabel
                        value={'true'}
                        name="deficiency"
                        control={<PurpleRadio />}
                        label="Sim"
                      />
                      <FormControlLabel
                        value={'false'}
                        name="deficiency"
                        control={<PurpleRadio />}
                        label="não"
                      />
                    </RadioGroup>
                    <FormHelperText>{errorList.deficiency}</FormHelperText>
                  </FormControl>
                </Grid>
              </Grid>
              {values.deficiency === 'true' ?
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
                      <FormLabel component="legend">Deficiência *</FormLabel>
                      <FormGroup>
                        <FormControlLabel
                          disabled={cegueiraDisabled}
                          control={
                            <Checkbox
                              checked={values.deficiency_type_blindness}
                              onChange={handleChange}
                            />}
                          name='deficiency_type_blindness'
                          label="Cegueira"
                        />
                        <FormControlLabel
                          disabled={baixaVisaoDisabled}
                          control={
                            <Checkbox
                              checked={values.deficiency_type_low_vision}
                              onChange={handleChange}
                            />}
                          name="deficiency_type_low_vision"
                          label="Baixa visão"
                        />
                        <FormControlLabel
                          disabled={surdezDisabled}
                          control={
                            <Checkbox
                              checked={values.deficiency_type_deafness}
                              onChange={handleChange}
                            />}
                          name="deficiency_type_deafness"
                          label="Surdez"
                        />
                        <FormControlLabel
                          disabled={defAuditivaDisabled}
                          control={
                            <Checkbox
                              checked={values.deficiency_type_disability_hearing}
                              onChange={handleChange}
                            />}
                          name="deficiency_type_disability_hearing"
                          label="Deficiência auditiva"
                        />
                        <FormControlLabel
                          disabled={surdoCegueiraDisabled}
                          control={
                            <Checkbox
                              checked={values.deficiency_type_deafblindness}
                              onChange={handleChange}
                            />}
                          name="deficiency_type_deafblindness"
                          label="Surdocegueira"
                        />
                        <FormControlLabel
                          disabled={defFisicaDisabled}
                          control={
                            <Checkbox
                              checked={values.deficiency_type_phisical_disability}
                              onChange={handleChange}
                            />}
                          name="deficiency_type_phisical_disability"
                          label="Deficiência Física"
                        />
                        <FormControlLabel
                          disabled={defIntelectualDisabled}
                          control={
                            <Checkbox
                              checked={values.deficiency_type_intelectual_disability}
                              onChange={handleChange}
                            />}
                          name="deficiency_type_intelectual_disability"
                          label="Deficiência Intelectual"
                        />
                        <FormControlLabel
                          disabled={deficienciaMultDisabled}
                          control={
                            <Checkbox
                              checked={values.deficiency_type_multiple_disabilities}
                              onChange={handleChange}
                            />}
                          name="deficiency_type_multiple_disabilities"
                          label="Deficiência Múltipla"
                        />
                        <FormControlLabel
                          disabled={transAutistaDisabled}
                          control={
                            <Checkbox
                              checked={values.deficiency_type_autism}
                              onChange={handleChange}
                            />}
                          name="deficiency_type_autism"
                          label="Transtorno do Espectro Autista"
                        />
                        <FormControlLabel
                          disabled={superDotacaoDisabled}
                          control={
                            <Checkbox
                              checked={values.deficiency_type_gifted}
                              onChange={handleChange}
                            />}
                          name="deficiency_type_gifted"
                          label="Altas Habilidades / Super Dotação"
                        />
                      </FormGroup>
                    </FormControl>
                  </Grid>
                </Grid>
                : null
              }
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

export default StepThree;
