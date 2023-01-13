import React, { useState } from "react";
import Grid from "@material-ui/core/Grid";
import { makeStyles, styled } from "@material-ui/core/styles";
import { Formik, Form } from "formik";
import Select from "react-select";
import AsyncSelect from "react-select/async";
import { FormLabel, FormControl, InputBase } from "@material-ui/core";
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

const BootstrapInput = styled(InputBase)(({ theme }) => ({
  'label + &': {
    marginTop: theme.spacing(3),
  },
  '& .MuiInputBase-input': {
    borderRadius: 4,
    position: 'relative',
    backgroundColor: theme.palette.background.paper,
    border: '1px solid #ced4da',
    fontSize: 16,
    padding: '10px 26px 10px 12px',
    transition: theme.transitions.create(['border-color', 'box-shadow']),
    // Use the system font instead of the default Roboto font.
    fontFamily: [
      '-apple-system',
      'BlinkMacSystemFont',
      '"Segoe UI"',
      'Roboto',
      '"Helvetica Neue"',
      'Arial',
      'sans-serif',
      '"Apple Color Emoji"',
      '"Segoe UI Emoji"',
      '"Segoe UI Symbol"',
    ].join(','),
    '&:focus': {
      borderRadius: 4,
      borderColor: '#80bdff',
      boxShadow: '0 0 0 0.2rem rgba(0,123,255,.25)',
    },
  },
}));

const StepSix = props => {
  const classes = useStyles();
  const { loadingButtom } = props;
  const [inputValue, setInputValue] = useState("");
  const [id, setId] = useState('')
  const [arrClassrooms, setArrClassrooms] = useState([]);
  const [inputValueClassroom, setInputValueClassroom] = useState("");

  const validationSchema = Yup.object().shape({
    school_identification: Yup.string().required("Campo obrigatório!"),
    classroom: Yup.string().required("Campo obrigatório!")
  });

  const initialValues = {
    school_identification: '12345678',
    classroom_inep_id: "1234567800",
    classroom: 423
  };

  console.log(id)
  const handleInputChange = newValue => {
    console.log(newValue)
    api.get("/school-identification-registration", {
      params: {
        include: {
          classroom: true
        }
      }
    }).then(response => {
      console.log(response.data)
      if (response.data.classroom) {
        var filterClassrooms = response.data.classroom.filter(classroom => classroom.name == newValue)
        setArrClassrooms(filterClassrooms);
      }
    });
    setInputValue(newValue);
  };

  const handleChange = newValue => {
    setInputValueClassroom(newValue);
  };

  const searchSchools = () => {
    console.log(inputValue)
    if (inputValue) {
      api.get("school-identification-registration").then(response => {
        console.log(response.data)
        return response.data.filter(school => school.name == inputValue)
      });

      // if (inputValue.trim().length >= 3) {
      //   setTimeout(() => {
      //     api.get("school-identification-registration").then(response => {
      //       console.log(response.data)
      //       setId(response.data.inep_id)
      //       callback(response.data.filter(school => school.name == inputValue));
      //     });
      //   }, 500);
    } else {
      return []
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
                    <Select
                      styles={customStyles}
                      cacheOptions
                      loadOptions={searchSchools}
                      defaultOptions
                      placeholder="Digite o nome da escola"
                      onChange={selectedOption => {
                        setId(selectedOption._id);
                      }}
                      input={<BootstrapInput onChange={(e)=> setInputValue(e.target.value)}/>}
                    // className={classes.selectField}
                    // getOptionValue={opt => opt.inepId}
                    // getOptionLabel={opt => opt.inepId + " - " + opt.name}
                    // loadingMessage={() => "Carregando"}
                    // noOptionsMessage={obj => {
                    //   if (obj.inputValue.trim().length >= 3) {
                    //     return "Nenhuma escola encontrada";
                    //   } else {
                    //     return "Digite 3 ou mais caracteres";
                    //   }
                    // }}
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
