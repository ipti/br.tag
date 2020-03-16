import React from "react";

// Router
import { useHistory } from "react-router-dom";

// Material UI
import Grid from "@material-ui/core/Grid";
import { makeStyles } from "@material-ui/core/styles";
import Alert from '@material-ui/lab/Alert';

// Components
import { BoxBig, BoxDiscriptionClassroom } from "../../components/Boxes";
import { TitleWithLine } from "../../components/Titles";
import List from "../../components/List";

// Assets
import MaleIcon from "../../assets/images/male-icon.png";
import SchoolIcon from "../../assets/images/house-icon.png";

// Styles
import styles from "./styles";

const useStyles = makeStyles(styles);

const Home = ({ data})  => {
  const classes = useStyles();
  let history = useHistory();

  const handleLink = link => {
    history.push(link);
  };

  const dependence = {
    "1": "Federal",
    "2": "Estadual",
    "3": "Munícipal",
    "4": "Privada"
  };

  const classrooms = data
    ? data.classrooms.map((classroom, index) => (
        <Grid
          className={classes.cursor}
          onClick={() => handleLink("/turmas/" + classroom._id)}
          key={index}
          item
          md={4}
          sm={4}
          xs={12}
        >
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
          <span title={data?.inepId} className={classes.truncate}>
            {data?.inepId}
          </span>
        </Grid>
        <Grid item md={4}>
          <p className={classes.label}>Dependência Administrativa</p>
          <span
            title={data?.administrative_dependence}
            className={classes.truncate}
          >
            {dependence[data?.administrative_dependence]}
          </span>
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
              <span title={data?.managerName} className={classes.truncate}>
                {data?.managerName}
              </span>
            </div>
          </div>
        </Grid>
        <Grid item md={12}>
          <div className={classes.lineGrayClean}></div>
        </Grid>
      </Grid>
      <Grid container direction="row" spacing={3}>
        <Grid container item md={5}>
          <Grid item md={2}>
            <div className={classes.iconHouse}>
              <img
                className={classes.floatLeft}
                src={SchoolIcon}
                width={38}
                alt="Icone de Endereço"
              />
            </div>
          </Grid>
          <Grid item md={10}>
            <div className={`${classes.floatLeft} ${classes.boxAddress}`}>
              <p className={classes.label}>Endereço</p>
              <span title={data?.address}  className={classes.truncate}>
              { data?.address }
              </span>
            </div>
          </Grid>
        </Grid>
        <Grid item md={2}>
          <p className={classes.label}>Cep</p>
          <span title={data?.cep}  className={classes.truncate}>
          { data?.cep }
          </span>
        </Grid>
        <Grid item md={4}>
          <p className={classes.label}>Cidade</p>
          <span title={data?.city}  className={classes.truncate}>
          { data?.city }
          </span>
        </Grid>
      </Grid>
      <Grid className={classes.boxClassroom} container direction="row">
        <TitleWithLine title="Turmas" />
      </Grid>
      <Grid container direction="row" spacing={5}>
        <List items={classrooms} >
          <Grid item xs={12} >
            <Alert variant="outlined" severity="warning">
              Nenhuma turma cadastrada
            </Alert>
          </Grid>
        </List>
      </Grid>
    </>
  );
};

export default Home;
