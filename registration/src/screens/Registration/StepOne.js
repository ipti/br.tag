import React from "react";
import Grid from "@material-ui/core/Grid";
import { makeStyles } from "@material-ui/core/styles";
import { ButtonPurple, ButtonLinePurple } from "../../components/Buttons";
import homeImg from "../../assets/images/illustration-home.png";
import styles from "./styles";

const useStyles = makeStyles(styles);

const Student = props => {
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
        <Grid item md={8} sm={12} xs={12}>
          <img src={homeImg} alt="" />
        </Grid>
        <Grid item md={8} sm={8} xs={8}>
          <h1>Possui Vínculo</h1>
          <p>Você estava matriculado na</p>
          <p>instituição no ano anterior?</p>
        </Grid>
      </Grid>
      <Grid
        className={`${classes.marginTop}`}
        container
        direction="row"
        justify="center"
        alignItems="center"
      >
        <Grid item md={2} sm={4} xs={4}>
          <ButtonPurple
            type="button"
            onClick={() => props.handleStudent(true)}
            title="Sim"
          />
        </Grid>
        <Grid className={classes.marginLeftButton} item md={2} sm={4} xs={4}>
          <ButtonLinePurple
            onClick={() => props.handleStudent(false)}
            type="button"
            title="Não"
          />
        </Grid>
      </Grid>
    </>
  );
};

export default Student;
