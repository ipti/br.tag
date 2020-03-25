import React from "react";
import { makeStyles } from "@material-ui/core/styles";
import { Link } from "react-router-dom";
import Grid from "@material-ui/core/Grid";
import IconMale from "../../assets/images/student-male-icon.png";
import IconWoman from "../../assets/images/student-woman-icon.png";
import styles from "./styles";
const useStyles = makeStyles(styles);

const BoxRegistration = props => {
  const { name, link, confirmed, sex, md, sm, xs } = props;
  const classes = useStyles();
  return (
    <Grid item md={md ? md : 4} sm={sm ? sm : 4} xs={xs ? xs : 12}>
      <Link to={link} className={`${classes.boxStudent} ${classes.floatLeft}`}>
        <div className={classes.iconStudent}>
          <img src={sex === "1" ? IconMale : IconWoman} alt="Icone de aluno" />
        </div>
        <div className={`${classes.floatLeft} ${classes.nameStudent}`}>
          <div title={name} className={`${classes.truncate}`}>
            {name}
          </div>
          <span className={classes.subtitleStudent}>Aluno</span>
          {confirmed === true && (
            <span className={`${classes.confimedCicle}`}></span>
          )}
          {confirmed === false && (
            <span className={`${classes.refusedCicle}`}></span>
          )}
        </div>
      </Link>
    </Grid>
  );
};

export default BoxRegistration;
