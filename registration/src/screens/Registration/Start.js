import React from "react";
import Grid from "@material-ui/core/Grid";
import { makeStyles } from "@material-ui/core/styles";
import { ButtonPurple } from "../../components/Buttons";
import homeImg from "../../assets/images/illustration-home.png";
import styles from "./styles";
import AsyncSelect from "react-select/async";
import { idSchool } from "../../services/auth";
import { useState } from "react";
import { FormControl, FormLabel } from "@material-ui/core";

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
  const [calendar_event, setCalendar_event] = useState()

  const date = Date(Date.now());


  const searchSchools = (inputValue, callback) => {
    if (inputValue.trim().length >= 3) {
      const buscaLowerCase = inputValue.toLowerCase();
      callback(props.schools.filter(school => school.name.toLowerCase().includes(buscaLowerCase)));
    }
  };

  

  

  const onButton = () => {
    if(calendar_event?.start_date <= date ){
      props.setIsActive(true)
      props.nextStep('1')
  } else {
    props.setIsActive(false)
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
                idSchool(selectedOption.inep_id);
                setCalendar_event(selectedOption.calendar_event.find(e => e.id === 1))
                
                
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
