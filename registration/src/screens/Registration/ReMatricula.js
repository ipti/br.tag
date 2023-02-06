import React, { useState } from "react";

// Material UI
import Grid from "@material-ui/core/Grid";
import { makeStyles } from "@material-ui/core/styles";
import { FormControl, FormLabel } from "@material-ui/core";

import Select from "react-select";
import AsyncSelect from "react-select/async";

// Components
import { TitleWithLine } from "../../components/Titles";
import { BoxStatus } from "../../components/Boxes";
import { ButtonPurple, ButtonLinePurple } from "../../components/Buttons";
import Loading from "../../components/Loading/CircularLoadingButtomActions";

// Assets
import IconMale from "../../assets/images/male-icon.png";
import IconStudent from "../../assets/images/student-male-icon.png";
import IconHouse from "../../assets/images/house-icon.png";
import IconClassroom from "../../assets/images/classroom-icon.png";

// Styles
import styles from "../Classroom/styles";
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
const Home = props => {
  const classes = useStyles();
  const [schoolInepFk, setSchoolInepFk] = useState('');
  const [inputValueClassroom, setInputValueClassroom] = useState("");

  const { data } = useFetchRequestSchoolRegistration({ id: getIdSchool() });

  if (!data) return null
  const handleChange = newValue => {
    setInputValueClassroom(newValue);
  };


  const {
    registration,
    handleSubmit,
  } = props;
  const student = registration ?? [];


  const nullableField = "-------------";

  const studentName = student?.name;
  const color_race = student?.color_race === 0 ? 'Branca' : student?.color_race === 2 ? 'Preta' : student?.color_race === 3 ? 'Parda' : student?.color_race === 4 ? 'Amarela' : student?.color_race === 5 ? 'Indígena' : 'Não especificado';
  const deficiency = student?.deficiency ? 'sim' : 'não';

  const studentBirthday = student?.birthday
  // ? format(studentDate, "dd/MM/yyyy")
  // : "";
  const status = student?.newStudent;

  const city = student?.city ?? nullableField;
  const state = student?.state ?? nullableField;


  const responsableName = student?.responsable_name ?? nullableField;
  const responsableCpf = student?.responsable_cpf ?? nullableField;

  const body = {
    name: studentName,
    birthday: studentBirthday,
    deficiency: student?.deficiency,
    color_race: student?.color_race,
    edcenso_city_fk: student?.edcenso_city_fk,
    edcenso_uf_fk: student?.edcenso_uf_fk,
    responsable_telephone: student?.responsable_telephone,
    responsable_name: student?.responsable_name,
    responsable_cpf: student?.responsable_cpf,
    student_identification: student?.id,
    sex: student?.sex,
    school_identification: schoolInepFk,
    classroom: inputValueClassroom,
    calendar_event: data.calendar_event.find(e => e.id === 1).id
  }


  return (
    <div style={{ height: '100%', padding: '10%' }}>
      <Grid className={classes.boxTitlePagination} container direction="row">
        <TitleWithLine title="Matrícula" />
      </Grid>
      <Grid container direction="row" spacing={3}>
        <Grid item md={5}>
          <img
            className={`${classes.floatLeft} ${classes.iconStudent}`}
            src={IconStudent}
            alt="Icone de aluno"
          />
          <div className={classes.floatLeft}>
            <p className={classes.label}>Aluno</p>
            {studentName}
          </div>
        </Grid>
        <Grid item md={3}>
          <p className={classes.label}>Nascimento</p>
          {studentBirthday}
        </Grid>
        <Grid item md={4}>
          <BoxStatus title={!status ? "Rematrícula" : "Novo Aluno"} />
        </Grid>
        <Grid item md={4}>
          <p className={classes.label}>Cor/Raça</p>
          {color_race}
        </Grid>
        <Grid item md={4}>
          <p className={classes.label}>Sexo</p>
          {student?.sex === 1 ? 'Maculino' : student?.sex === 2 ? 'Femenino' : ''}
        </Grid>
        <Grid item md={4}>
          <p className={classes.label}>Possui Deficiência</p>
          {deficiency}
        </Grid>
        <Grid item md={12}>
          <div className={classes.lineGrayClean}></div>
        </Grid>
      </Grid>
      <Grid container direction="row" spacing={3}>
        <Grid item md={5}>
          <img
            className={`${classes.floatLeft} ${classes.iconResponsable}`}
            src={IconMale}
            alt="Icone de Responsável"
          />
          <div className={classes.floatLeft}>
            <p className={classes.label}>Responsável</p>
            {responsableName}
          </div>
        </Grid>
        <Grid item md={4}>
          <p className={classes.label}>CPF</p>
          {responsableCpf}
        </Grid>
        <Grid item md={3}>
          <p className={classes.label}>Telefone</p>
          {student?.responsable_telephone}
        </Grid>
        <Grid item md={6}>
          <div className={classes.floatLeft}>
            <p className={classes.label}>Name da Mãe</p>
            {student?.mother_name}
          </div>
        </Grid>
        <Grid item md={6}>
          <div className={classes.floatLeft}>
            <p className={classes.label}>Name do Pai</p>
            {student?.father_name}
          </div>
        </Grid>
        <Grid item md={12}>
          <div className={classes.lineGrayClean}></div>
        </Grid>
      </Grid>
      <Grid container direction="row" spacing={3}>
        <Grid item md={5}>
          <img
            className={`${classes.floatLeft} ${classes.iconHouse}`}
            src={IconHouse}
            alt="Icone de Endereço"
          />
          <div className={classes.floatLeft}>
            <p className={classes.label}>Cidade</p>
            {city}
          </div>
        </Grid>
        <Grid item md={3}>
          <p className={classes.label}>Estado</p>
          {state}
        </Grid>
        <Grid item md={12}>
          <div className={classes.lineGrayClean}></div>
        </Grid>
      </Grid>
      {/* 
      <Grid
        className={`${classes.contentMain} ${classes.marginTop30}`}
        container
        direction="row"
        justifyContent="center"
        alignItems="center"
      >
        <Grid item xs={12}>
          <Grid item md={6}>
            <img
              className={`${classes.floatLeft} ${classes.iconClassroom}`}
              src={IconClassroom}
              alt="Icone de Turma"
            />
          </Grid>
          <Grid>
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
          </Grid>
          <div className={classes.formFieldError}>
          </div>
        </Grid>
      </Grid> */}
      {/* <Grid container direction="row" spacing={3}>
        <Grid item md={5}>
          <img
            className={`${classes.floatLeft} ${classes.iconClassroom}`}
            src={IconClassroom}
            alt="Icone de Turma"
          />
          <div className={classes.floatLeft}>
            <p className={classes.label}>Turma</p>
          </div>
        </Grid>
        <Grid item md={3}>
          <p className={classes.label}>Modalidade</p>
        </Grid>
        <Grid item md={4}>
          <p className={classes.label}>Turno</p>
          Manhã
        </Grid>
      </Grid> */}
      <Grid
        className={classes.boxButtons}
        container
        direction="row"
        spacing={3}
      >
        {!props?.loadingIcon ? (
          <>
            <Grid item md={3}>
              <ButtonPurple
                onClick={() => handleSubmit(body)}
                type="button"
                title="Confirmar"
              />
            </Grid>
          </>
        ) : (
          <Grid item md={3}>
            <Loading />
          </Grid>
        )}
      </Grid>
    </div>
  );
};

export default Home;
