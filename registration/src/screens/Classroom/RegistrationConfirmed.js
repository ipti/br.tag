import React from "react";
import Grid from "@material-ui/core/Grid";
import { makeStyles } from "@material-ui/core/styles";
import styles from "./styles";
import { TitleWithLine } from "../../components/Titles";
import { BoxStatus } from "../../components/Boxes";
import IconMale from "../../assets/images/male-icon.png";
import IconStudent from "../../assets/images/student-male-icon.png";
import IconHouse from "../../assets/images/house-icon.png";
import IconClassroom from "../../assets/images/classroom-icon.png";
import { ButtonPurple, ButtonLinePurple } from "../../components/Buttons";

const useStyles = makeStyles(styles);

const Home = props => {
  const classes = useStyles();
  const { registration, handleSubmit } = props;

  let modalityDefault = { "1": "Ensno Regular" };

  let student_name = registration.data && registration.data.student.name;
  let student_birthday =
    registration.data && registration.data.student.birthday;
  let status = registration.data && registration.data.student.newStudent;
  let filiation1 = registration.data && registration.data.student.filiation1;
  let address = registration.data && registration.data.student.address;
  let cep = registration.data && registration.data.student.cep;
  let city = registration.data && registration.data.student.city;
  let responsableCpf =
    registration.data && registration.data.student.responsableCpf
      ? registration.data.student.responsableCpf
      : "-------------";
  let responsableBirthday =
    registration.data && registration.data.student.responsableBirthday
      ? registration.data.student.responsableBirthday
      : "-------------";

  let classroom_name = registration.data && registration.data.classroom.name;
  let modality =
    registration.data && modalityDefault[registration.data.classroom.modality];

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
            {student_name}
          </div>
        </Grid>
        <Grid item md={3}>
          <p className={classes.label}>Nascimento</p>
          {student_birthday}
        </Grid>
        <Grid item md={4}>
          <BoxStatus title={!status ? "Transferência" : "Novo"} />
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
            {filiation1}
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
            <p className={classes.label}>Truma</p>
            {classroom_name}
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
      </Grid>
    </>
  );
};

export default Home;
