import React, { useState } from 'react';

// Material UI
import AppBar from '@material-ui/core/AppBar';
import Toolbar from '@material-ui/core/Toolbar';
import IconButton from '@material-ui/core/IconButton';
import MenuIcon from '@material-ui/icons/Menu';
import Typography from '@material-ui/core/Typography';
import useMediaQuery from '@material-ui/core/useMediaQuery';

// Components
import MenuMobile from '../../components/MenuMobile';

// Data
import menu from '../../data/menu';

import { styles } from './styles';

const Header = () => {
  const classes = styles();
  const isMobile = useMediaQuery((theme) => theme.breakpoints.down('sm'));

  const [openMenu, setOpenMenu] = useState(false);

  const toggleMenu = () => {
    setOpenMenu((open) => !open);
  };

  return (
    <>
      <AppBar classes={{ root: classes.root }} position="static">
        <Toolbar>
          {isMobile && (
            <IconButton
              edge="start"
              className={classes.menuButton}
              color="inherit"
              aria-label="menu"
              onClick={toggleMenu}
            >
              <MenuIcon />
            </IconButton>
          )}
          <Typography variant="h6" className={classes.title}>
            CRAS
          </Typography>
        </Toolbar>
      </AppBar>
      <MenuMobile open={openMenu} items={menu} onClose={toggleMenu} />
    </>
  );
};

export default Header;
