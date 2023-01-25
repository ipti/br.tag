import React from "react";
import { format, parseISO } from "date-fns";

// Material UI
import Grid from "@material-ui/core/Grid";
import { makeStyles } from "@material-ui/core/styles";

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
import styles from "./styles";

const useStyles = makeStyles(styles);

const Home = props => {
  const classes = useStyles();

  const {
    registration,
    handleSubmit,
    classroom
  } = props;
  const student = registration ?? [];


  const classroomOne = classroom.data ?? [];

  const modalityDefault = { "1": "Ensno Regular" };

  const nullableField = "-------------";

  const studentName = student?.name;
  const cpf = student?.cpf;
  const color_race = student?.color_race === 0 ? 'Branca' : student?.color_race === 2 ? 'Preta' : student?.color_race === 3 ? 'Parda' : student?.color_race === 4 ? 'Amarela' : student?.color_race === 5 ? 'Indígena' : 'Não especificado';
  const deficiency = student?.deficiency ? 'sim' : 'não';

  const studentDate = student?.birthday ? parseISO(student?.birthday) : "";
  const studentBirthday = student?.birthday
  // ? format(studentDate, "dd/MM/yyyy")
  // : "";

  const status = student?.newStudent;

  const address = student?.address ?? nullableField;
  const cep = student?.cep ?? nullableField;
  const city = student?.city ?? nullableField;
  const number = student?.number ?? nullableField;
  const neighborhood = student?.neighborhood ?? nullableField;
  const complement = student?.complement === '' ? nullableField : student?.complement;
  const state = student?.state ?? nullableField;


  const responsableName = student?.responsable_name ?? nullableField;
  const responsableCpf = student?.responsable_cpf ?? nullableField;



  const classroomName = classroomOne?.name ?? nullableField;
  const modality = classroomOne?.modality === 1 ? 'Ensino Regular' : classroomOne?.modality === 2 ? 'Educação Especial' : classroomOne?.modality === 3 ? 'Educação de jovens e adultos (EJA)' : classroomOne?.modality === 4 ? 'Educação profissional' : nullableField;


  const data = {
    name: studentName,
    birthday: studentBirthday,
    deficiency: student?.deficiency,
    color_race: student?.color_race, 
    edcenso_city_fk: student?.edcenso_city_fk,
    edcenso_uf_fk: student?.edcenso_uf_fk,
    responsable_name: student?.responsableName,
    responsableCpf: student?.responsableCpf,
    responsable_telephone: student?.responsable_telephone,
    sex: student?.sex,
    school_identification: student?.school_inep_id_fk,
    classroom: student?.classroom_fk,
    calendar_event: student?.calendar_event_fk,
    year: 2023
  }

  return (
    <>
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
          <BoxStatus title={!status ? "Transferência" : "Novo Aluno"} />
        </Grid>
        <Grid item md={3}>
          <p className={classes.label}>CPF</p>
          {cpf}
        </Grid>
        <Grid item md={3}>
          <p className={classes.label}>Cor/Raça</p>
          {color_race}
        </Grid>
        <Grid item md={3}>
          <p className={classes.label}>Sexo</p>
          {student?.sex == 1 ? 'Maculino' : student?.sex == 2 ? 'Femenino' : ''}
        </Grid>
        <Grid item md={3}>
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
            <p className={classes.label}>Endereço</p>
            {address}
          </div>
        </Grid>
        <Grid item md={4}>
          <p className={classes.label}>Bairro</p>
          {neighborhood}
        </Grid>

        <Grid item md={3}>
          <p className={classes.label}>Número</p>
          {number}
        </Grid>
        <Grid item md={3}>
          <p className={classes.label}>Complemento</p>
          {complement}
        </Grid>
        <Grid item md={3}>
          <p className={classes.label}>CEP</p>
          {cep}
        </Grid>
        <Grid item md={3}>
          <p className={classes.label}>Cidade</p>
          {city}
        </Grid>
        <Grid item md={3}>
          <p className={classes.label}>Estado</p>
          {state}
        </Grid>
        <Grid item md={2}>
          <BoxStatus title={student?.zone === 2 ? "Urbana" : student?.zone === 1 ? "Rural" : ''} />
        </Grid>
        <Grid item md={12}>
          <div className={classes.lineGrayClean}></div>
        </Grid>
      </Grid>
      <Grid container direction="row" spacing={3}>
        <Grid item md={5}>
          <img
            className={`${classes.floatLeft} ${classes.iconClassroom}`}
            src={IconClassroom}
            alt="Icone de Turma"
          />
          <div className={classes.floatLeft}>
            <p className={classes.label}>Turma</p>
            {classroomName}
          </div>
        </Grid>
        <Grid item md={3}>
          <p className={classes.label}>Modalidade</p>
          {modality}
        </Grid>
        <Grid item md={4}>
          <p className={classes.label}>Turno</p>
          Manhã
        </Grid>
      </Grid>
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
                onClick={() => handleSubmit(data)}
                type="button"
                title="Confirmar"
              />
            </Grid>
            <Grid item md={3}>
              <ButtonLinePurple
                onClick={() => handleSubmit(false)}
                type="button"
                title="Recusar"
              />
            </Grid>
          </>
        ) : (
          <Grid item md={3}>
            <Loading />
          </Grid>
        )}
      </Grid>
    </>
  );
};

export default Home;
