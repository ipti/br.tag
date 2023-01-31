import React, { useState } from "react";
import Grid from "@material-ui/core/Grid";
import { makeStyles} from "@material-ui/core/styles";
import { Formik, Form } from "formik";
import Select from "react-select";
import { FormLabel, FormControl} from "@material-ui/core";
import { ButtonPurple } from "../../components/Buttons";
import styles from "./styles";
import * as Yup from "yup";
import Loading from "../../components/Loading/CircularLoadingButtomActions";
import { useFetchRequestSchoolRegistration } from "../../query/registration";
import { getIdSchool } from "../../services/auth";

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

// const BootstrapInput = styled(InputBase)(({ theme }) => ({
//   'label + &': {
//     marginTop: theme.spacing(3),
//   },
//   '& .MuiInputBase-input': {
//     borderRadius: 4,
//     position: 'relative',
//     backgroundColor: theme.palette.background.paper,
//     border: '1px solid #ced4da',
//     fontSize: 16,
//     padding: '10px 26px 10px 12px',
//     transition: theme.transitions.create(['border-color', 'box-shadow']),
//     // Use the system font instead of the default Roboto font.
//     fontFamily: [
//       '-apple-system',
//       'BlinkMacSystemFont',
//       '"Segoe UI"',
//       'Roboto',
//       '"Helvetica Neue"',
//       'Arial',
//       'sans-serif',
//       '"Apple Color Emoji"',
//       '"Segoe UI Emoji"',
//       '"Segoe UI Symbol"',
//     ].join(','),
//     '&:focus': {
//       borderRadius: 4,
//       borderColor: '#80bdff',
//       boxShadow: '0 0 0 0.2rem rgba(0,123,255,.25)',
//     },
//   },
// }));

const StepSix = props => {
  const classes = useStyles();
  const { loadingButtom } = props;
  const [inepId, setInepId] = useState('')
  const [schoolInepFk, setSchoolInepFk] = useState('');
  const [inputValueClassroom, setInputValueClassroom] = useState("");

  const validationSchema = Yup.object().shape({
    school_identification: Yup.string().required("Campo obrigatório!"),
    classroom: Yup.string().required("Campo obrigatório!")
  });

  const { data } = useFetchRequestSchoolRegistration({ id: getIdSchool() })
  if(!data) return null

  const initialValues = {
    school_identification: schoolInepFk,
    classroom_inep_id: inepId,
    classroom: inputValueClassroom,
    calendar_event: data.calendar_event.find(e => e.id === 1).id
  };

  const handleChange = newValue => {
    setInputValueClassroom(newValue);
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
                className={`${classes.contentMain} ${classes.marginTop30}`}
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
                    <FormLabel>Turma *</FormLabel>
                    <Select
                      styles={customStyles}
                      className="basic-single"
                      classNamePrefix="select"
                      isSearchable={true}
                      placeholder="Selecione a Turma"
                      options={data.classroom}
                      onChange={selectedOption => {
                        handleChange(selectedOption.id);
                        setSchoolInepFk(selectedOption.school_inep_fk)
                        setInepId(selectedOption.inep_id)
                      }}
                      getOptionValue={opt => opt.classroom}
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
                justifyContent="center"
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
