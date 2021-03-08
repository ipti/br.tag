import React from 'react';

// Material UI
import { makeStyles } from '@material-ui/core/styles';

// Conponents
import User from '../../components/User';
import Menu from '../../components/Menu';

// Services
import { isAuthenticated, getUser } from '../../services/auth';

// Data
import menu from '../../data/menu';

// Styles
import { styles } from './styles';

const useStyles = makeStyles(styles);

const Sidebar = () => {
  const classes = useStyles();
  const user = getUser();

  return (
    <div className={classes.root}>
      {isAuthenticated() && <User name={user?.name} />}
      <Menu items={menu} />
    </div>
  );
};

export default Sidebar;
