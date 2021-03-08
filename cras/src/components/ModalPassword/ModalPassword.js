import React from 'react';

// Components
import Modal from '../../components/Modal';
import UserMessage from '../../components/UserMessage';

// Assets
import { ReactComponent as Illustration } from '../../assets/images/recuperar-senha.svg';

const ModalPassword = ({ open, onClose }) => {
  const message = 'Para alterar a sua senha entre em contato com o administrador do sistema.';

  const handleClose = () => {
    onClose();
  };

  return (
    <Modal open={open} onClose={handleClose}>
      <UserMessage message={message} onClose={handleClose}>
        <Illustration />
      </UserMessage>
    </Modal>
  );
};

export default ModalPassword;
