import React from 'react';
import Grid from '@material-ui/core/Grid';
import { makeStyles } from '@material-ui/core/styles';
import { styles } from './styles';
import homeImg from '../../assets/images/not-found.svg';
import { Link } from 'react-router-dom';

const useStyles = makeStyles(styles);

const NotFound = () => {
  const classes = useStyles();

  return (
    <>
      <Grid
        className={classes.contentStart}
        container
        direction="row"
        justify="center"
        alignItems="center"
      >
        <Grid item md={8} sm={12} xs={12}>
          <img src={homeImg} alt="" />
        </Grid>
        <Grid item md={8} sm={12} xs={12}>
          <h1 className={classes.title}>Página não encontrada</h1>
          <div>A página que você tentou acessar não está mais disponível.</div>
          <div>Verifique se o endereço informado está correto.</div>
        </Grid>
        <Grid item md={8} sm={12} xs={12}>
          <Link className={classes.link} to="/">
            Início
          </Link>
        </Grid>
      </Grid>
    </>
  );
};

export default NotFound;
