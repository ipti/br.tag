import React from 'react';
import Grid from '@material-ui/core/Grid';
import { makeStyles } from '@material-ui/core/styles';
import Header from '../Header';
import Sidebar from '../Sidebar';
import { styles } from './styles';
import { Hidden } from '@material-ui/core';
const useStyles = makeStyles(styles);

const Layout = (props) => {
  const { children } = props;
  const classes = useStyles();

  return (
    <Grid container direction="column">
      <Grid container item md={12} className={classes.header}>
        <Header />
      </Grid>
      <Grid container item md={12} className={classes.content}>
        <Hidden smDown >
          <Grid item md={3} sm={4}>
            <Sidebar />
          </Grid>
        </Hidden>
        <Grid className={classes.contentMain} item md={9} sm={8}>
          <div className={classes.boxContentMain}>{children}</div>
        </Grid>
      </Grid>
    </Grid>
  );
};

export default Layout;
