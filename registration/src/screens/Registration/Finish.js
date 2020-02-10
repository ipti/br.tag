import React from "react";
import Grid from "@material-ui/core/Grid";
import { makeStyles } from "@material-ui/core/styles";
import FinishImg from "../../assets/images/illustration-success.png";
import styles from "./styles";

const useStyles = makeStyles(styles);

const Finish = props => {
  const classes = useStyles();
  return (
    <>
      <Grid
        className={`${classes.contentStart} ${classes.contentBond}`}
        container
        direction="row"
        justify="center"
        alignItems="center"
      >
        <Grid item md={8} sm={8} xs={8}>
          <img src={FinishImg} alt="" />
        </Grid>
        <Grid item md={8} sm={8} xs={9}>
          <p>Sua matrícula foi realizada, anote seu</p>
          <p>código de inscrição abaixo</p>
        </Grid>
      </Grid>
      <Grid
        className={`${classes.contentStart} ${classes.contentBond}`}
        container
        direction="row"
        justify="center"
        alignItems="center"
      >
        <Grid
          className={classes.boxNumberRegistration}
          item
          md={3}
          sm={4}
          xs={6}
        >
          {props.registration && props.registration.id}
        </Grid>
      </Grid>
    </>
  );
};

export default Finish;
