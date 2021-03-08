import React, { useEffect, useState } from 'react';
import Icon from '@material-ui/core/Icon';
import CloseIcon from '@material-ui/icons/Close';
import IconButton from '@material-ui/core/IconButton';
import Snackbar from '@material-ui/core/Snackbar';
import SnackbarContent from '@material-ui/core/SnackbarContent';

const Alert = (props) => {
  const [icon, setIcon] = useState('');
  const [backgroundColor, setBackgroundColor] = useState('');
  const [open, setOpen] = useState(false);
  const [autoHideDuration, setAutoHideDuration] = useState(null);
  const [message, setMessage] = useState('');

  useEffect(() => {
    if (props.open !== undefined) {
      setOpen(props.open);
    }
  }, [props.open]);

  useEffect(() => {
    if (props.autoHideDuration !== undefined) {
      setAutoHideDuration(props.autoHideDuration);
    }
  }, [props.autoHideDuration]);

  useEffect(() => {
    if (props.type !== undefined) {
      let color = '';
      let icon = '';
      switch (props.type) {
        case 'danger':
          color = '#d32f2f';
          icon = 'fa fa-exclamation-circle';
          break;
        case 'warning':
          color = '#ffa000';
          icon = 'fa fa-exclamation-triangle';
          break;
        case 'info':
          color = '#1976d2';
          icon = 'fa fa-info-circle';
          break;
        case 'success':
          color = '#43a047';
          icon = 'fa fa-check-circle';
          break;
        default:
          color = '#ffffff';
          icon = 'fa fa-info-circle';
      }

      setBackgroundColor(color);
      setIcon(icon);
    }
  }, [props.type]);

  useEffect(() => {
    if (props.message !== undefined) {
      setMessage(props.message);
    }
  }, [props.message]);

  const handleClose = () => {
    if (props.handleClose) {
      props.handleClose();
    }
  };

  return (
    <Snackbar
      open={open}
      anchorOrigin={{ vertical: 'bottom', horizontal: 'left' }}
      autoHideDuration={autoHideDuration}
      onClose={handleClose}
      style={{ whiteSpace: 'pre-line' }}
    >
      <SnackbarContent
        action={[
          <IconButton key="close" aria-label="Close" color="inherit" onClick={handleClose}>
            <CloseIcon />
          </IconButton>
        ]}
        message={
          <span style={{ display: 'flex', alignItems: 'center' }}>
            <Icon className={`${icon}`} style={{ marginRight: '4px' }} />
            {message}
          </span>
        }
        style={{
          background: backgroundColor
        }}
      />
    </Snackbar>
  );
};

export default Alert;
