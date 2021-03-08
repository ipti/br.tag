import React from 'react';
import { makeStyles } from '@material-ui/core/styles';
import styles from './styles';
const useStyles = makeStyles(styles);

const Title = (props) => {
  const { title } = props;
  const classes = useStyles();

  return (
    <div className={classes.boxTitlePagination}>
      <h1 className={classes.title}>{title}</h1>
      <span className={classes.lineBlue} />
    </div>
  );
};

export default Title;
