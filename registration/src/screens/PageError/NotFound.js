import React from "react";
import Grid from "@material-ui/core/Grid";
import { makeStyles } from "@material-ui/core/styles";
import styles from "./styles";
import homeImg from "../../assets/images/illustration-home.png";
const useStyles = makeStyles(styles);

const NotFound = () => {
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
          <h1>Error 404</h1>
          <p>Página Não Encontrada.</p>
        </Grid>
      </Grid>
    </>
  );
};

export default NotFound;
