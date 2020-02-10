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
        justify="center"
        alignItems="center"
      >
        <Grid item md={8} sm={12} xs={12}>
          <img src={homeImg} alt="" />
        </Grid>
        <Grid item md={8} sm={12} xs={12}>
          <h1>Matrícula Online</h1>
          <p>Bem-vindo ao Matrícula online, para </p>
          <p>iniciar clique no botão abaixo</p>
        </Grid>
      </Grid>
      <Grid
        className={`${classes.marginTop}`}
        container
        direction="row"
        justify="center"
        alignItems="center"
      >
        <Grid item md={2} sm={6} xs={6}>
          <ButtonPurple
            type="button"
            onClick={() => props.nextStep(1)}
            title="Iniciar"
          />
        </Grid>
      </Grid>
    </>
  );
};

export default Start;
