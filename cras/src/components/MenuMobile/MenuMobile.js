import React from 'react';

// Material UI
import Drawer from '@material-ui/core/Drawer';
import { makeStyles } from '@material-ui/core/styles';

// Components
import Menu from '../../components/Menu';

// Styles
import { styles } from './styles';

const useStyles = makeStyles(styles);

const MenuMobile = ({ open, items, onClose }) => {
  const classes = useStyles();

  return (
    <Drawer
      anchor="left"
      open={open}
      onClose={onClose}
      PaperProps={{
        classes: { root: classes.drawer }
      }}
    >
      <Menu items={items} />
    </Drawer>
  );
};

export default MenuMobile;
