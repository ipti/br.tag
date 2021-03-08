import React from 'react';
import { makeStyles } from '@material-ui/core/styles';
import styles from './styles';

const useStyles = makeStyles(styles);

const Button = ({
  children,
  variant = 'contained',
  size = 'large',
  color = 'primary',
  className = '',
  ...restProps
}) => {
  const classes = useStyles({ variant, size, color });

  return (
    <button className={`${classes.button} ${className}`} {...restProps}>
      {children}
    </button>
  );
};

export default Button;
