import React from 'react';

// Material UI
import { makeStyles } from '@material-ui/core/styles';
import Collapse from '@material-ui/core/Collapse';

// Third party
import { Link } from 'react-router-dom';

// Styles
import { styles } from './styles';

const useStyles = makeStyles(styles);

const MenuItem = ({ Icon, name, path, active = false, submenu = [] }) => {
  const classes = useStyles({ active });

  const renderSubmenu = () => (
    <Collapse in={active}>
      <ul className={classes.submenu}>
        {submenu.map((item, index) => (
          <li key={index} className={classes.menuItem}>
            <Link className={classes.link} to={item.path}>
              <span>{item.name}</span>
            </Link>
          </li>
        ))}
      </ul>
    </Collapse>
  );

  return (
    <>
      <li className={`${classes.menuItem} ${classes.outlined}`}>
        <Link className={classes.link} to={path}>
          {Icon && <Icon />}
          <span>{name}</span>
        </Link>
      </li>
      {!!submenu?.length && renderSubmenu(submenu)}
    </>
  );
};

export default MenuItem;
