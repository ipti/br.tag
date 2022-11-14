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
  TextField,
  FormGroup,
  Checkbox
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
import { useState } from "react";

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
      placeholderChar={"_"}
      showMask
    />
  );
};

const StepThree = props => {
  const classes = useStyles();
  const [cegueiraDisabled, setCegueiraDisabled ] = useState(false);
  const [baixaVisaoDisabled , setBaixaVisaoDisabled ] = useState(false);
  const [surdezDisabled , setSurdezDisabled ] = useState(false);
  const [defAuditivaDisabled , setDefAuditivaDisabled ] = useState(false);
  const [surdoCegueiraDisabled , setSurdoCegueiraDisabled ] = useState(false);
  const [defFisicaDisabled , setDefFisicaDisabled ] = useState(false);
  const [defIntelectualDisabled , setDefIntelectualDisabled ] = useState(false);
  const [deficienciaMultDisabled , setDeficienciaMultDisabled ] = useState(false);
  const [transAutistaDisabled , setTransAutistaDisabled ] = useState(false); 
  const [superDotacaoDisabled , setSuperDotacaoDisabled ] = useState(false);

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
    sex: props?.student?.sex ?? "",
    cpf: "" ?? "",
    deficient: "" ?? "",
    cegueira: false,
    baixaVisao: false,
    surdez: false,
    defAuditiva: false,
    surdoCegueira: false,
    defFisica: false,
    defIntelectual: false,
    deficienciaMult: false,
    transAutista: false,
    superDotacao: false
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
            cpf: touched.cpf && errors.cpf,
          };

          if(values.cegueira){
            setBaixaVisaoDisabled(true);
            setSurdezDisabled(true);
            setSurdoCegueiraDisabled(true);
          }else{
            setBaixaVisaoDisabled(false);
            setSurdezDisabled(false);
          }

          if(values.baixaVisao){
            setCegueiraDisabled(true);
            setSurdoCegueiraDisabled(true);
          }
          if(!values.baixaVisao && !values.cegueira){
            setCegueiraDisabled(false);
            setSurdoCegueiraDisabled(false);
          }

          if(values.surdez){
            setCegueiraDisabled(true);
            setSurdoCegueiraDisabled(true);
            setDefAuditivaDisabled(true)
          }else{
            setDefAuditivaDisabled(false)
          }

          if(values.defAuditiva){
            setSurdoCegueiraDisabled(true);
            setSurdezDisabled(true);
          }

          if(values.surdoCegueira){
            setCegueiraDisabled(true);
            setBaixaVisaoDisabled(true);
            setSurdezDisabled(true);
            setDefAuditivaDisabled(true);
          }

          if(values.defIntelectual){
            setSuperDotacaoDisabled(true)
          } else{
            setSuperDotacaoDisabled(false)
          }

          if(values.superDotacao){
            setDefIntelectualDisabled(true)
          }else{
            setDefIntelectualDisabled(false)
          }
          
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
                    <FormLabel>Aluno *</FormLabel>
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
                    error={errorList.studentName}
                  >
                    <FormLabel>Nº do CPF *</FormLabel>
                    <TextField
                      name="cpf"
                      variant="outlined"
                      InputProps={{
                        inputComponent: TextMaskCpf,
                        value: values.cpf,
                        onChange: handleChange
                      }}
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
                    error={errorList.colorRace}
                  >
                    <FormLabel>Cor/Raça *</FormLabel>
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
                    <FormLabel component="legend">Sexo *</FormLabel>
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
                    <FormLabel component="legend">Possui Deficiência? *</FormLabel>
                    <RadioGroup
                      value={values.deficient}
                      name="deficient"
                      onChange={handleChange}
                      row
                    >
                      <FormControlLabel
                        value="2"
                        name="deficient"
                        control={<PurpleRadio />}
                        label="Sim"
                      />
                      <FormControlLabel
                        value="1"
                        name="deficient"
                        control={<PurpleRadio />}
                        label="não"
                      />
                    </RadioGroup>
                    <FormHelperText>{errorList.sex}</FormHelperText>
                  </FormControl>
                </Grid>
              </Grid>
              {values.deficient === '2' ? 
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
                  >
                     <FormLabel component="legend">Deficiência *</FormLabel>
                     <FormGroup>
                      <FormControlLabel
                      disabled={cegueiraDisabled}
                      control={
                        <Checkbox 
                          checked={values.cegueira}
                          onChange={handleChange}
                        />} 
                      name='cegueira'
                      label="Cegueira" 
                      />
                      <FormControlLabel 
                        disabled={baixaVisaoDisabled} 
                        control={
                          <Checkbox 
                            checked={values.baixaVisao}
                            onChange={handleChange}
                          />} 
                        name="baixaVisao"
                        label="Baixa visão" 
                      />
                      <FormControlLabel
                        disabled={surdezDisabled} 
                        control={
                          <Checkbox 
                            checked={values.surdez}
                            onChange={handleChange}
                          />} 
                        name="surdez"
                        label="Surdez" 
                      />
                      <FormControlLabel
                        disabled={defAuditivaDisabled}
                        control={
                          <Checkbox 
                            checked={values.defAuditiva}
                            onChange={handleChange}
                          />} 
                        name="defAuditiva"
                        label="Deficiência auditiva" 
                      />
                      <FormControlLabel
                        disabled={surdoCegueiraDisabled} 
                        control={
                          <Checkbox
                            checked={values.surdoCegueira}
                            onChange={handleChange}
                          />}
                        name="surdoCegueira" 
                        label="Surdocegueira" 
                      />
                      <FormControlLabel
                        disabled={defFisicaDisabled} 
                        control={
                          <Checkbox 
                            checked={values.defFisica}
                            onChange={handleChange}
                          />}
                        name="defFisica" 
                        label="Deficiência Física" 
                      />
                      <FormControlLabel
                        disabled={defIntelectualDisabled} 
                        control={
                          <Checkbox
                            checked={values.defIntelectual}
                            onChange={handleChange} 
                          />}
                        name="defIntelectual" 
                        label="Deficiência Intelectual" 
                      />
                      <FormControlLabel
                        disabled={deficienciaMultDisabled} 
                        control={
                          <Checkbox 
                            checked={values.deficienciaMult}
                            onChange={handleChange}
                          />}
                        name="deficienciaMult" 
                        label="Deficiência Múltipla" 
                      />
                      <FormControlLabel
                        disabled={transAutistaDisabled} 
                        control={
                          <Checkbox 
                            checked={values.transAutista}
                            onChange={handleChange}
                          />}
                        name="transAutista" 
                        label="Transtorno do Espectro Autista" 
                      />
                      <FormControlLabel
                      disabled={superDotacaoDisabled} 
                        control={
                          <Checkbox
                            checked={values.superDotacao}
                            onChange={handleChange}
                          />} 
                        name="superDotacao"
                        label="Altas Habilidades / Super Dotação" 
                      />
                    </FormGroup>
                    <FormHelperText>{errorList.fone}</FormHelperText>
                  </FormControl>
                </Grid>
              </Grid>
              : null
              }
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
