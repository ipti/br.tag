import React from "react";
import { makeStyles } from "@material-ui/core/styles";
import styles from "./styles";

const useStyles = makeStyles(styles);

const ButtonPurple = props => {
  const classes = useStyles();
  return (
    <button className={`${classes.buttomPurple}`} {...props}>
      {props.title}
    </button>
  );
};

export default ButtonPurple;
