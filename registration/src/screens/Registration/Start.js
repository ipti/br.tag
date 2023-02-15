import { FormControl, FormLabel } from "@material-ui/core";
import Grid from "@material-ui/core/Grid";
import { makeStyles } from "@material-ui/core/styles";
import React, { useContext, useState } from "react";
import AsyncSelect from "react-select/async";
import homeImg from "../../assets/images/illustration-home.png";
import { ButtonPurple } from "../../components/Buttons";
import RegistrationContext from '../../containers/Registration/context';
import styles from "./styles";
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
const Start = props => {
  const classes = useStyles();
  const [startDate, setStartDate] = useState()
  const [endDate, setEndDate] = useState()
  const { setIdSchool, setIdEvent, idSchool, idEvent } = useContext(RegistrationContext);
  const datenow = Date.now();
  const date = new Date(datenow)


  const searchSchools = (inputValue, callback) => {
    if (inputValue.trim().length >= 3) {
      const buscaLowerCase = inputValue.toLowerCase();
      callback(props.schools.filter(school => school.name.toLowerCase().includes(buscaLowerCase)));
    }
  };





  const onButton = () => {
    if (startDate <= date.getTime() && date.getTime() <= endDate) {
      props.setIsActive(true)
      props.next('1', {school_identification: idSchool, event_pre_registration: idEvent})
    } else {
      props.setIsActive(false)
      props.next('1', {school_identification: idSchool, event_pre_registration: idEvent})
    }

  }
  return (
    <>
      <Grid
        className={classes.contentStart}
        container
        direction="row"
        justifyContent="center"
        alignItems="center"
      >
        <Grid item xs={12}>
          <img src={homeImg} alt="" />
        </Grid>
        <Grid item xs={12}>
          <h1>Matrícula Online</h1>
          <p>
            Bem-vindo ao Matrícula online, para <br /> iniciar escolha a escola e clique no botão
            abaixo
          </p>
        </Grid>
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
                setIdSchool(selectedOption.inep_id);
                setIdEvent(selectedOption.event_pre_registration[0].id)
                if (selectedOption.event_pre_registration[0]) {
                  setStartDate(new Date(selectedOption.event_pre_registration[0].start_date).getTime())
                  setEndDate(new Date(selectedOption.event_pre_registration[0].end_date).getTime())
                } else {
                  props.setIsActive(false)
                }
              }}
              className={classes.selectField}
              getOptionValue={opt => opt.inep_id}
              getOptionLabel={opt => opt.inep_id + " - " + opt.name}
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
        </Grid>
      </Grid>
      <Grid
        className={`${classes.marginTop}`}
        container
        direction="row"
        justifyContent="center"
        alignItems="center"
      >
        <Grid item xs={6}>
          <ButtonPurple
            type="button"
            onClick={onButton}
            title="Iniciar"
          />
        </Grid>
      </Grid>
    </>
  );
};

export default Start;
