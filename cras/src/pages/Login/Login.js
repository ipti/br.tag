import React, { useState } from 'react';

// Material UI
import Grid from '@material-ui/core/Grid';
import { Hidden, Typography } from '@material-ui/core';
import { makeStyles } from '@material-ui/core/styles';

// Components
import Button from '../../components/Button';
import LoginForm from './components/LoginForm';
import ModalUser from '../../components/ModalUser';
import ModalPassword from '../../components/ModalPassword';

// Assets
import securityLogin from '../../assets/images/security-login.svg';

// Styles
import { styles } from './styles';

const useStyles = makeStyles(styles);

const Login = () => {
  const classes = useStyles();

  const [openUser, setOpenUser] = useState(false);
  const [openPassword, setOpenPassword] = useState(false);

  const handleCloseUser = () => {
    setOpenUser(false);
  };

  const handleOpenUser = () => {
    setOpenUser(true);
  };

  const handleClosePassword = () => {
    setOpenPassword(false);
  };

  const handleOpenPassword = () => {
    setOpenPassword(true);
  };

  return (
    <>
      <Grid container>
        <Hidden mdDown>
          <Grid item lg={8}>
            <div className={classes.content}>
              <h1 className={classes.title}>
                Bem-vindo ao <strong>CRAS</strong>
              </h1>
              <p className={classes.description}>
                Efetue o login ao lado para acessar sua conta, caso não possua realize o seu
                cadastro.
              </p>
              <Button
                size="medium"
                variant="contained"
                color="secondary"
                className={classes.button}
                onClick={handleOpenUser}
              >
                Cadastre-se
              </Button>
            </div>
          </Grid>
        </Hidden>

        <Grid item lg={4} xs={12}>
          <div className={classes.sidebar}>
            <div className={classes.header}>
              <span className={classes.brandName}>CRAS</span>
              <span className={classes.pageName}>Login</span>
            </div>
            <div className={classes.logo}>
              <img src={securityLogin} alt="Login" title="Login" />
            </div>
            <div className={classes.form}>
              <LoginForm />
            </div>
            <div className={classes.footer}>
              <Typography color="textSecondary">Esqueceu a senha?</Typography>
              <Button
                size="medium"
                variant="text"
                color="secondary"
                className={classes.textButton}
                onClick={handleOpenPassword}
              >
                clique aqui
              </Button>
            </div>
          </div>
        </Grid>
      </Grid>
      <ModalUser open={openUser} onClose={handleCloseUser} />
      <ModalPassword open={openPassword} onClose={handleClosePassword} />
    </>
  );
};

export default Login;
