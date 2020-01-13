import React from "react";
import Grid from "@material-ui/core/Grid";
import { makeStyles } from "@material-ui/core/styles";
import styles from "./styles";
import { BoxBig, BoxDiscriptionClassroom } from "../../components/Boxes";
import MaleIcon from "../../assets/images/male-icon.png";
import SchoolIcon from "../../assets/images/school-icon.png";

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
            textRight="Ativa"
          >
            <BoxDiscriptionClassroom
              title="Vagas Preenchidas"
              registrationConfirmed={`${classroom.registrationConfirmed}`}
            />
            <BoxDiscriptionClassroom
              title="Vagas Restante"
              registrationRemaining={`${classroom.registrationRemaining}`}
            />
          </BoxBig>
        </Grid>
      ))
    : [];
  return (
    <>
      <Grid container direction="row">
        <Grid
          className={classes.boxTitlePagination}
          item
          md={12}
          sm={12}
          xs={12}
        >
          <h1 className={classes.title}>{data && data.name}</h1>
          <span className={classes.linePurple} />
        </Grid>
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
          <div className={`${classes.floatLeft} ${classes.boxImageMale}`}>
            <img src={MaleIcon} alt="Icone do Gestor" />
          </div>
          <div className={classes.floatLeft}>
            <div className={`${classes.floatLeft} ${classes.boxManager}`}>
              <p className={classes.label}>Gestor</p>
              {data && data.managerName}
            </div>
          </div>
        </Grid>
        <Grid item md={12}>
          <div className={classes.lineGrayClean}></div>
        </Grid>
      </Grid>
      <Grid container direction="row" spacing={3}>
        <Grid item md={5}>
          <div className={`${classes.floatLeft} ${classes.boxSchool}`}>
            <img src={SchoolIcon} alt="Icone da Escola" />
          </div>
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
      <Grid className={classes.boxCalssroom} container direction="row">
        <Grid
          className={classes.boxTitlePagination}
          item
          md={12}
          sm={12}
          xs={12}
        >
          <h1 className={classes.title}>Turmas</h1>
          <span className={classes.linePurple} />
        </Grid>
      </Grid>
      <Grid container direction="row" spacing={5}>
        {classrooms}
      </Grid>
    </>
  );
};

export default Home;
