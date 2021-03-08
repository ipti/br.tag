import React from 'react';

// Material UI
import { makeStyles } from '@material-ui/core/styles';
import InputIcon from '@material-ui/icons/Input';
import Typography from '@material-ui/core/Typography';

// Third party
import { useHistory } from 'react-router-dom';

// Services
import { logout } from '../../services/auth';

// Assets
import { ReactComponent as Avatar } from '../../assets/images/avatar.svg';

// Styles
import { styles } from './styles';

const useStyles = makeStyles(styles);

const User = ({ name }) => {
  const classes = useStyles();
  const history = useHistory();

  const handleLogout = () => {
    logout();
    history.push('/login');
  };

  return (
    <div className={classes.container}>
      <div className={classes.avatar}>
        <Avatar />
      </div>
      <div className={classes.content}>
        <Typography component="h6">{name}</Typography>
        <button onClick={handleLogout} className={classes.button}>
          <InputIcon className={classes.icon} />
          Sair
        </button>
      </div>
    </div>
  );
};

export default User;
