import React from "react";
import { makeStyles } from "@material-ui/core/styles";
import IconClassroom from "../../assets/images/classroom-icon.svg";
import Grid from "@material-ui/core/Grid";
import styles from "./styles";
const useStyles = makeStyles(styles);

const BoxVacancies = props => {
  const { title, quantity, background, md, sm, xs } = props;
  const classes = useStyles();

  const backgroundColor = () => {
    switch (background) {
      case "pink":
        return classes.backgroundPink;
      case "purple":
        return classes.backgroundPurple;
      default:
        return classes.backgroundBlue;
    }
  };

  return (
    <Grid item md={md ? md : 2} sm={sm ? sm : 2} xs={xs ? xs : 2}>
      <div className={`${classes.boxVacancies} ${backgroundColor()}`}>
        <img src={IconClassroom} alt="Icon Turmas" />
        <h1 className={classes.quantity}>{quantity}</h1>
        <p className={classes.vacanciesTitle}>{title}</p>
      </div>
    </Grid>
  );
};

export default BoxVacancies;
