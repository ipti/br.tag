import React, { useState } from "react";

//Material-UI
import {
  FormLabel,
  FormControl,
  RadioGroup,
  Radio,
  FormControlLabel,
  Grid,
  TextField,
  FormHelperText
} from "@material-ui/core";
import { makeStyles, withStyles } from "@material-ui/core/styles";


import { ButtonPurple } from "../../components/Buttons";

//style
import styles from "./styles";

// Third party
import * as Yup from "yup";
import { Formik, Form } from "formik";

import api from "../../services/api";
import Loading from "../../components/Loading/CircularLoadingButtomActions";


import styleBase from "../../styles";
import MaskedInput from "react-text-mask";
import { useEffect } from "react";

const useStyles = makeStyles(styles);

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


const StepFive = props => {
  const [errorCep, setErrorCep] = useState(false);
  const classes = useStyles();
  const { loadingButtom } = props;
 
  

  const validationSchema = Yup.object().shape({
    cep: Yup.string().required("Campo obrigatório!"),
    endereco: Yup.string().required("Campo obrigatório!"),
    numero: Yup.string().required("Campo obrigatório!"),
    bairro: Yup.string().required("Campo obrigatório!"),
    estado: Yup.string().required("Campo obrigatório!"),
    cidade: Yup.string().required("Campo obrigatório!"),
    residenceZone: Yup.string().required("Campo obrigatório!"),
  });

  const TextMaskCep = props => {
    const { inputRef, ...others } = props;
  
    return (
      <MaskedInput
        {...others}
        ref={ref => {
          inputRef(ref ? ref.inputElement : null);
        }}
        mask={[/\d/, /\d/, /\d/, /\d/, /\d/, "-", /\d/, /\d/, /\d/]}
        placeholderChar={"\u2000"}
        showMask
      />
    );
  };


  // useEffect(()=>{
  //   props.dispatch({type: "GET_ADDRESS", data: '49043130'})
  // },[])

  const initialValues = {
    cep: "",
    endereco: "",
    numero: "",
    complemento: "",
    bairro: "",
    estado: "",
    cidade: "",
    residenceZone: ""
  };

  const checkCep = (e, setFieldValue) =>{
      const cep =  e.target.value.replace(/\D/g, '');
      if (cep?.length !== 8) {
        return;
      }

      fetch(`https://viacep.com.br/ws/${cep}/json/`)
      .then(res => res.json()).then(data =>{
        if(data.erro){
          setErrorCep(true);
        }else{
        setFieldValue("bairro", data.bairro);
        setFieldValue("cidade", data.localidade);
        setFieldValue("estado", data.uf);
        setFieldValue("endereco", data.logradouro);
        setErrorCep(false);
        }
        
      })
  }

  const PurpleRadio = withStyles({
    root: {
      "&$checked": {
        color: styleBase.colors.purple
      }
    },
    checked: {}
  })(props => <Radio color="default" {...props} />);


  return (
    <>
      <Formik
        initialValues={initialValues}
        onSubmit={values => props.next('6', values)}
        validationSchema={validationSchema}
        validateOnChange={false}
        enableReinitialize
      >
        {({ errors, values, touched, handleChange, handleSubmit, setFieldValue }) => {

          const errorList = {
            cep: touched.cep && errors.cep,
            endereco: touched.endereco && errors.endereco,
            numero: touched.numero && errors.numero,
            bairro: touched.bairro && errors.bairro,
            estado: touched.estado && errors.estado,
            residenceZone: touched.residenceZone && errors.residenceZone,
            cidade: touched.cidade && errors.cidade
          };
          return (
            <Form>
              <Grid
                className={`${classes.contentMain} ${classes.marginTop}`}
                container
                direction="row"
                justify="center"
                alignItems="center"
              >
                <Grid item xs={12}>
                  <FormControl
                    component="fieldset"
                    className={classes.formControl}
                    error={errorList.cep}
                  >
                    <FormLabel>CEP *</FormLabel>
                    <TextField
                      name="cep"
                      InputProps={{
                        inputComponent: TextMaskCep,
                        value: values.cep,
                        onChange: handleChange
                      }}
                      onBlur={(e) => checkCep(e, setFieldValue)}
                      variant="outlined"
                      className={classes.textField}
                      error={errorList.cep || errorCep}
                    />
                    <FormHelperText>{errorList.cep}</FormHelperText>
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
                    error={errorList.endereco}
                  >
                    <FormLabel>Endereço *</FormLabel>
                    <TextField
                      name="endereco"
                      value={values.endereco}
                      onChange={handleChange}
                      variant="outlined"
                      className={classes.textField}
                      error={errorList.endereco}
                    />
                    <FormHelperText>{errorList.endereco}</FormHelperText>
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
                    error={errorList.numero}
                  >
                    <FormLabel>Número *</FormLabel>
                    <TextField
                      name="numero"
                      value={values.numero}
                      onChange={handleChange}
                      variant="outlined"
                      className={classes.textField}
                      error={errorList.numero}
                    />
                    <FormHelperText>{errorList.numero}</FormHelperText>
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
                  >
                    <FormLabel>Complemento</FormLabel>
                    <TextField
                      name="complemento"
                      value={values.complemento}
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
                    error={errorList.bairro}
                  >
                    <FormLabel>Bairro *</FormLabel>
                    <TextField
                      name="bairro"
                      value={values.bairro}
                      onChange={handleChange}
                      variant="outlined"
                      className={classes.textField}
                      error={errorList.bairro}
                    />
                    <FormHelperText>{errorList.bairro}</FormHelperText>
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
                    error={errorList.estado}
                  >
                    <FormLabel>Estado *</FormLabel>
                    <TextField
                      name="estado"
                      value={values.estado}
                      onChange={handleChange}
                      variant="outlined"
                      className={classes.textField}
                      error={errorList.estado}
                    />
                    <FormHelperText>{errorList.estado}</FormHelperText>
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
                    error={errorList.cidade}
                  >
                    <FormLabel>Cidade *</FormLabel>
                    <TextField
                      name="cidade"
                      value={values.cidade}
                      onChange={handleChange}
                      variant="outlined"
                      className={classes.textField}
                      error={errorList.cidade}
                    />
                    <FormHelperText>{errorList.cidade}</FormHelperText>
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
                    <FormLabel component="legend">Zona *</FormLabel>
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
                  {!loadingButtom ? (
                    <ButtonPurple
                      onClick={handleSubmit}
                      type="submit"
                      title="Continuar"
                    />
                  ) : (
                    <Loading />
                  )}
                </Grid>
              </Grid>
            </Form>
          );
        }}
      </Formik>
    </>
  );
};

export default StepFive;
