import React, { useState } from 'react';

// Material UI
import { makeStyles } from '@material-ui/core/styles';

// Components
import Modal from '../../components/Modal';
import UserMessage from '../../components/UserMessage';
import UserForm from '../UserForm';

// Assets
import { ReactComponent as UserIllustration } from '../../assets/images/cadastro-usuario.svg';

// Styles
import styles from './styles';

const useStyles = makeStyles(styles);

const ModalUser = ({ open, onClose }) => {
  const classes = useStyles();

  const [title, setTitle] = useState('Cadastro de usuário');
  const [isSuccess, setIsSuccess] = useState(false);
  const message = 'Usuário cadastrado, aguarde a aprovação do administrador do sistema.';

  const handleClose = () => {
    onClose();
  };

  const handleSuccess = () => {
    setTitle('');
    setIsSuccess(true);
  };

  return (
    <Modal title={title} open={open} onClose={handleClose}>
      {!isSuccess ? (
        <UserForm onClose={handleClose} onSuccess={handleSuccess} />
      ) : (
        <div className={classes.transition}>
          <UserMessage message={message} onClose={handleClose}>
            <UserIllustration />
          </UserMessage>
        </div>
      )}
    </Modal>
  );
};

export default ModalUser;
