import React from "react";
import { makeStyles } from "@material-ui/core/styles";
import styles from "./styles";

const useStyles = makeStyles(styles);

const ButtonLinePurple = props => {
  const classes = useStyles();
  return (
    <button className={`${classes.buttomLinePurple}`} {...props}>
      {props.title}
    </button>
  );
};

export default ButtonLinePurple;
