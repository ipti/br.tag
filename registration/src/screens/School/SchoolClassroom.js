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

const Home = ({ data })  => {
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
    ? data.school.classroom.map((classroom, index) => (
        <Grid
          className={classes.cursor}
          onClick={() => handleLink("/turmas/" + classroom.id)}
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

    const school = data.school

    console.log(school)

  return (
    <>
      <Grid container direction="row">
        {school  && <TitleWithLine title={school .name} />}
      </Grid>
      <Grid container direction="row" spacing={3}>
        <Grid item md={3}>
          <p className={classes.label}>Código do Inep</p>
          <span title={school ?.inep_id} className={classes.truncate}>
            {school ?.inep_id}
          </span>
        </Grid>
        <Grid item md={4}>
          <p className={classes.label}>Dependência Administrativa</p>
          <span
            title={school ?.administrative_dependence}
            className={classes.truncate}
          >
            {dependence[school?.administrative_dependence]}
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
              {school.manager_name ?
                <span title={school?.manager_name} className={classes.truncate}>
                {school?.manager_name}
              </span> : <span>
                Não especificado
              </span>
              }
              
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
              <span title={school?.address}  className={classes.truncate}>
              { school?.address }
              </span>
            </div>
          </Grid>
        </Grid>
        <Grid item md={2}>
          <p className={classes.label}>Cep</p>
          <span title={school?.cep}  className={classes.truncate}>
          { school?.cep }
          </span>
        </Grid>
        <Grid item md={4}>
          <p className={classes.label}>Cidade</p>
          <span title={school?.city}  className={classes.truncate}>
          { school?.edcenso_city.name }
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
