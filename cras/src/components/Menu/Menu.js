import React from 'react';

// Material UI
import { makeStyles } from '@material-ui/core/styles';

// Third party
import { useHistory } from 'react-router-dom';

// Components
import MenuItem from '../../components/MenuItem';

// Styles
import { styles } from './styles';

const useStyles = makeStyles(styles);

const Menu = ({ items }) => {
  const classes = useStyles();
  const history = useHistory();

  const isActive = (item) => {
    const currentPath = history.location?.pathname;

    const subitems = item?.submenu ?? [];
    const items = [item, ...subitems];

    return items.some((menuItem) => menuItem?.path === currentPath);
  };

  return (
    <nav className={classes.root}>
      <ul className={classes.menu}>
        {items.map(({ Icon, name, path, submenu }, index) => (
          <MenuItem
            key={index}
            name={name}
            path={path}
            submenu={submenu}
            Icon={Icon}
            active={isActive({ path, submenu })}
          />
        ))}
      </ul>
    </nav>
  );
};

export default Menu;
