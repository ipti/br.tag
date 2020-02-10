import React from "react";
import { makeStyles } from "@material-ui/core/styles";
import styles from "./styles";
import IconSchedule from "../../components/Svg/IconScheduleWhite";
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
        <IconSchedule />
      </div>
      <div className={classes.floatLeft}>
        <div className={classes.boxDescriptionSchedule}>{title}</div>
        <div className={classes.boxDescriptionScheduleSubtitle}>{subtitle}</div>
      </div>
    </div>
  );
};

export default BoxDiscriptionSchedule;
