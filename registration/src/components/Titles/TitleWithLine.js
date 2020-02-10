import React from "react";
import { makeStyles } from "@material-ui/core/styles";
import Grid from "@material-ui/core/Grid";
import styles from "./styles";
const useStyles = makeStyles(styles);

const TitleWithLine = props => {
  const { title } = props;
  const classes = useStyles();

  return (
    <Grid className={classes.boxTitlePagination} item md={12} sm={12} xs={12}>
      <h1 className={classes.title}>{title}</h1>
      <span className={classes.linePurple} />
    </Grid>
  );
};

export default TitleWithLine;
