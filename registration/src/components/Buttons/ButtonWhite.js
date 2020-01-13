import React from "react";
import { makeStyles } from "@material-ui/core/styles";
import styles from "./styles";

const useStyles = makeStyles(styles);

const ButtonWhite = props => {
  const classes = useStyles();
  return (
    <button className={`${classes.buttomWhite}`} {...props}>
      {props.title}
    </button>
  );
};

export default ButtonWhite;
