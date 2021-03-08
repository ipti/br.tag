import React from 'react';

// Material UI
import Typography from '@material-ui/core/Typography';
import { makeStyles } from '@material-ui/core/styles';

// Components
import Button from '../../components/Button';

// Styles
import { styles } from './styles';

const useStyles = makeStyles(styles);

const UserMessage = ({ children, message, onClose }) => {
  const classes = useStyles();

  const handleClose = () => {
    onClose();
  };

  return (
    <div className={classes.container}>
      {children}
      <Typography className={classes.message} color="textSecondary" align="center">
        {message}
      </Typography>
      <Button className={classes.button} size="medium" type="submit" onClick={handleClose}>
        Fechar
      </Button>
    </div>
  );
};

export default UserMessage;
