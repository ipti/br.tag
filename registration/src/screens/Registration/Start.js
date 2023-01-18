import React from "react";
import Grid from "@material-ui/core/Grid";
import { makeStyles } from "@material-ui/core/styles";
import { ButtonPurple } from "../../components/Buttons";
import homeImg from "../../assets/images/illustration-home.png";
import styles from "./styles";

const useStyles = makeStyles(styles);

const Start = props => {
  const classes = useStyles();

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
            Bem-vindo ao Matrícula online, para <br /> iniciar clique no botão
            abaixo
          </p>
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
            onClick={() => props.nextStep('5')}
            title="Iniciar"
          />
        </Grid>
      </Grid>
    </>
  );
};

export default Start;
