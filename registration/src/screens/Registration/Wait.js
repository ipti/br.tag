import React from "react";
import Grid from "@material-ui/core/Grid";
import { makeStyles } from "@material-ui/core/styles";
import homeImg from "../../assets/images/illustration-home.png";
import styles from "./styles";

const useStyles = makeStyles(styles);

const Wait = props => {
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
        <Grid item xs={12}>
          <img src={homeImg} alt="Ilustração" />
        </Grid>
        <Grid item xs={12}>
          <h1>Matrícula Online</h1>
          <p>Não estamos em período de matrícula. <br /> Consulte o calenário</p>
        </Grid>
      </Grid>
    </>
  );
};

export default Wait;
