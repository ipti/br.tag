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
    registration: { data },
    handleSubmit
  } = props;
  const { student, classroom } = data ?? { student: {}, classroom: {} };

  const modalityDefault = { "1": "Ensno Regular" };

  const nullableField = "-------------";

  const studentName = student?.name;

  const studentDate = student?.birthday ? parseISO(student?.birthday) : "";
  const studentBirthday = student?.birthday
    ? format(studentDate, "dd/MM/yyyy")
    : "";

  const status = student?.newStudent;

  const address = student?.address ?? nullableField;
  const cep = student?.cep ?? nullableField;
  const city = student?.city ?? nullableField;

  const responsableName = student?.responsableName ?? nullableField;
  const responsableCpf = student?.responsableCpf ?? nullableField;

  const responsableDate = student?.responsableBirthday
    ? parseISO(student?.responsableBirthday)
    : "";
  const responsableBirthday = student?.responsableBirthday
    ? format(responsableDate, "dd/MM/yyyy")
    : "";

  const classroomName = classroom?.name ?? nullableField;
  const modality = classroom && modalityDefault[classroom?.modality];

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
        <Grid item md={3}>
          <p className={classes.label}>Nascimento</p>
          {responsableBirthday}
        </Grid>
        <Grid item md={4}>
          <p className={classes.label}>CPF</p>
          {responsableCpf}
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
        <Grid item md={3}>
          <p className={classes.label}>CEP</p>
          {cep}
        </Grid>
        <Grid item md={4}>
          <p className={classes.label}>Cidade</p>
          {city}
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
                onClick={() => handleSubmit(true)}
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
