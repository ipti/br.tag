import React from "react";
import Grid from "@material-ui/core/Grid";
import { makeStyles } from "@material-ui/core/styles";
import styles from "./styles";
import { BoxBig, BoxDiscriptionClassroom } from "../../components/Boxes";
import { TitleWithLine } from "../../components/Titles";
import MaleIcon from "../../assets/images/male-icon.png";
import SchoolIcon from "../../assets/images/house-icon.png";

const useStyles = makeStyles(styles);

const Home = props => {
  const { data } = props;
  const classes = useStyles();
  let classrooms = data
    ? data.classrooms.map((classroom, index) => (
        <Grid key={index} item md={4} sm={4} xs={12}>
          <BoxBig
            title={classroom.name}
            subtitle="Turma"
            addCursor={true}
            textRight=""
          >
            <BoxDiscriptionClassroom
              title="Preenchidas"
              registrationConfirmed={`${classroom.registrationConfirmed}`}
            />
            <BoxDiscriptionClassroom
              title="Restante"
              registrationRemaining={`${classroom.registrationRemaining}`}
            />
          </BoxBig>
        </Grid>
      ))
    : [];
  return (
    <>
      <Grid container direction="row">
        {data && <TitleWithLine title={data.name} />}
      </Grid>
      <Grid container direction="row" spacing={3}>
        <Grid item md={3}>
          <p className={classes.label}>Código do Inep</p>
          {data && data.inepId}
        </Grid>
        <Grid item md={4}>
          <p className={classes.label}>Dependência Administrativa</p>
          {data && data.managerName}
        </Grid>
        <Grid item md={4}>
          <img
            className={`${classes.floatLeft} ${classes.boxImageMale}`}
            src={MaleIcon}
            alt="Icone do Gestor"
          />
          <div className={classes.floatLeft}>
            <div className={`${classes.floatLeft} ${classes.boxManager}`}>
              <p className={classes.label}>Gestor</p>
              <div className={classes.truncate}>{data && data.managerName}</div>
            </div>
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
            src={SchoolIcon}
            alt="Icone de Endereço"
          />
          <div className={`${classes.floatLeft} ${classes.boxAddress}`}>
            <p className={classes.label}>Endereço</p>
            {data && data.address}
          </div>
        </Grid>
        <Grid item md={2}>
          <p className={classes.label}>Cep</p>
          {data && data.cep}
        </Grid>
        <Grid item md={4}>
          <p className={classes.label}>Cidade</p>
          {data && data.city}
        </Grid>
      </Grid>
      <Grid className={classes.boxClassroom} container direction="row">
        <TitleWithLine title="Turmas" />
      </Grid>
      <Grid container direction="row" spacing={5}>
        {classrooms}
      </Grid>
    </>
  );
};

export default Home;
