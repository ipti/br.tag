import React, { useState } from "react";
import Grid from "@material-ui/core/Grid";
import { makeStyles } from "@material-ui/core/styles";
import { Formik, Form } from "formik";
import Select from "react-select";
import AsyncSelect from "react-select/async";
import { FormLabel, FormControl } from "@material-ui/core";
import { ButtonPurple } from "../../components/Buttons";
import styles from "./styles";
import * as Yup from "yup";
import api from "../../services/api";
import Loading from "../../components/Loading/CircularLoadingButtomActions";

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

const StepSix = props => {
  const classes = useStyles();
  const { loadingButtom } = props;
  const [inputValue, setInputValue] = useState("");
  const [arrClassrooms, setArrClassrooms] = useState([]);
  const [inputValueClassroom, setInputValueClassroom] = useState("");

  const validationSchema = Yup.object().shape({
    school_identification: Yup.string().required("Campo obrigatório!"),
    classroom: Yup.string().required("Campo obrigatório!")
  });

  const initialValues = {
    school_identification: inputValue,
    classroom: inputValueClassroom
  };

  const handleInputChange = newValue => {
    api.get("/external/school/" + newValue).then(response => {
      if (response.data) {
        setArrClassrooms(response.data.school.classrooms);
      }
    });
    setInputValue(newValue);
  };

  const handleChange = newValue => {
    setInputValueClassroom(newValue);
  };

  const searchSchools = (inputValue, callback) => {
    if (inputValue.trim().length >= 3) {
      setTimeout(() => {
        api.get("/external/searchschool/" + inputValue).then(response => {
          callback(response.data);
        });
      }, 500);
    } else {
      callback(props?.schools);
    }
  };

  return (
    <>
      <Formik
        initialValues={initialValues}
        onSubmit={values => props.next(7, values)}
        validationSchema={validationSchema}
        validateOnChange={false}
        enableReinitialize
      >
        {props => {
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
                  >
                    <FormLabel>Escola *</FormLabel>
                    <AsyncSelect
                      styles={customStyles}
                      cacheOptions
                      loadOptions={searchSchools}
                      defaultOptions
                      placeholder="Digite o nome da escola"
                      onChange={selectedOption => {
                        handleInputChange(selectedOption._id);
                      }}
                      className={classes.selectField}
                      getOptionValue={opt => opt.inepId}
                      getOptionLabel={opt => opt.inepId + " - " + opt.name}
                      loadingMessage={() => "Carregando"}
                      noOptionsMessage={obj => {
                        if (obj.inputValue.trim().length >= 3) {
                          return "Nenhuma escola encontrada";
                        } else {
                          return "Digite 3 ou mais caracteres";
                        
                        
                        
                        
                        
                        }
                      }}
                    />
                  </FormControl>
                  <div className={classes.formFieldError}>
                    {props.errors.schoolInepId}
                  </div>
                </Grid>
              </Grid>
              <Grid
                className={`${classes.contentMain} ${classes.marginTop30}`}
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
                    <FormLabel>Turma *</FormLabel>
                    <Select
                      styles={customStyles}
                      className="basic-single"
                      classNamePrefix="select"
                      isSearchable={true}
                      placeholder="Selecione a Turma"
                      options={arrClassrooms}
                      onChange={selectedOption => {
                        handleChange(selectedOption._id);
                      }}
                      getOptionValue={opt => opt._id}
                      getOptionLabel={opt => opt.name}
                      loadingMessage={() => "Carregando"}
                      noOptionsMessage={obj => {
                        if (obj.inputValue.trim().length >= 3) {
                          return "Nenhuma turma encontrada";
                        } else {
                          return "Digite 3 ou mais caracteres";
                        }
                      }}
                    />
                  </FormControl>
                  <div className={classes.formFieldError}>
                    {props.errors.classroom}
                  </div>
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
                      onClick={props.handleSubmit}
                      type="submit"
                      title="Finalizar"
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

export default StepSix;
