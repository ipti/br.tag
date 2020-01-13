import React from "react";
import { makeStyles } from "@material-ui/core/styles";
import styles from "./styles";
import IconSchedule from "../../assets/images/schedule-icon.svg";
const useStyles = makeStyles(styles);

const BoxDiscriptionSchedule = props => {
  const { title, subtitle, color } = props;
  const classes = useStyles();
  return (
    <div className={`${classes.boxDescriptionCalssroom} ${classes.floatLeft}`}>
      <div
        className={`${classes.boxQuantity} ${classes.floatLeft} ${
          color === "pink"
            ? classes.boxQuantityBackgroundPink
            : classes.boxQuantityBackgroundPurple
        }`}
      >
        <img src={IconSchedule} alt="Icone Cronograma" />
      </div>
      <div className={classes.floatLeft}>
        <div className={classes.boxDescriptionCalssroomTitle}>{title}</div>
        <div>{subtitle}</div>
      </div>
    </div>
  );
};

export default BoxDiscriptionSchedule;
