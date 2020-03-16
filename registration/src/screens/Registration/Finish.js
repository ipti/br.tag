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
        <Grid item xs={12}>
          <img src={FinishImg} alt="" />
        </Grid>
        <Grid item xs={12}>
          <p>
            Matrícula realizada com sucesso,
            <br /> anote seu código de inscrição
          </p>
        </Grid>
      </Grid>
      <Grid
        className={`${classes.contentStart} ${classes.contentBond}`}
        container
        direction="row"
        justify="center"
        alignItems="center"
      >
        <Grid className={classes.boxNumberRegistration} item xs={10}>
          {props?.registration}
        </Grid>
      </Grid>
    </>
  );
};

export default Finish;
