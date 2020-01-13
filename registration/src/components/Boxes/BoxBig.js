import React from "react";
import { makeStyles } from "@material-ui/core/styles";
import ImgSchool from "../../assets/images/school-icon.png";
import styles from "./styles";
const useStyles = makeStyles(styles);

const BoxBig = props => {
  const { children, title, subtitle, textRight, addCursor } = props;
  const classes = useStyles();

  const headWithImage = () => (
    <div>
      <img
        src={ImgSchool}
        className={classes.iconHouse}
        alt="Icone da escola"
      />
      <span className={`${classes.floatRight} ${classes.textRight}`}>
        {textRight}
      </span>
    </div>
  );

  const headWithoutImage = () => (
    <div className={classes.boxWithoutImage}>
      {!textRight && (
        <>
          <div className={classes.title}>{title}</div>
          <div className={classes.subtitle}>{subtitle}</div>
        </>
      )}
      {textRight && (
        <>
          <span className={classes.title}>{title}</span>
          <span className={`${classes.floatRight} ${classes.textRight}`}>
            {textRight}
          </span>
        </>
      )}
    </div>
  );

  return (
    <div
      className={`${classes.contentBox} ${classes.floatLeft} ${
        addCursor ? classes.addCursor : ""
      }`}
    >
      <div>{title ? headWithoutImage() : headWithImage()}</div>
      <div>{children}</div>
    </div>
  );
};

export default BoxBig;
