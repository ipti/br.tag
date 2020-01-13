import React from "react";
import { makeStyles } from "@material-ui/core/styles";
import styles from "./styles";
const useStyles = makeStyles(styles);

const BoxDiscriptionClassroom = props => {
  const { title, registrationConfirmed, registrationRemaining } = props;
  const classes = useStyles();
  return (
    <div
      className={`${classes.boxDescriptionCalssroom} ${classes.floatLeft} ${
        registrationConfirmed ? classes.marginBox : ""
      } `}
    >
      <div
        className={`${classes.boxQuantity} ${classes.floatLeft} ${
          registrationConfirmed
            ? classes.boxQuantityBackgroundPurple
            : classes.boxQuantityBackgroundPink
        }`}
      >
        {registrationConfirmed ? registrationConfirmed : registrationRemaining}
      </div>
      <div className={classes.floatLeft}>
        <div className={classes.boxDescriptionCalssroomTitle}>{title}</div>
        <div>Vagas</div>
      </div>
    </div>
  );
};

export default BoxDiscriptionClassroom;
