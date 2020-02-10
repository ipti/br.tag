import React from "react";
import { makeStyles } from "@material-ui/core/styles";
import styles from "./styles";
const useStyles = makeStyles(styles);

const BoxStatus = props => {
  const { title } = props;
  const classes = useStyles();

  return <div className={`${classes.boxStatusStudent}`}>{title}</div>;
};

export default BoxStatus;
