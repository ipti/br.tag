/**
 * Support Page Modal
 */
import React from 'react';
import Button from '@material-ui/core/Button';
import { Modal, ModalHeader, ModalBody, ModalFooter, FormGroup, Label, Input } from 'reactstrap';

const SupportPage = ({ isOpen, onCloseSupportPage, onSubmit }) => (
    <Modal isOpen={isOpen} toggle={onCloseSupportPage}>
        <ModalHeader toggle={onCloseSupportPage}>Support</ModalHeader>
        <ModalBody>
            <FormGroup>
                <Label for="email">Email</Label>
                <Input type="email" name="email" id="email" disabled defaultValue="support@theironnetwork.org" />
            </FormGroup>
            <FormGroup>
                <Label for="subject">Subject</Label>
                <Input type="text" name="subject" id="subject" placeholder="Enter Subject" />
            </FormGroup>
            <FormGroup>
                <Label for="message">Enter Message</Label>
                <Input type="textarea" name="message" id="message" />
            </FormGroup>
        </ModalBody>
        <ModalFooter>
            <Button variant="raised" color="primary" className="text-white bg-primary" onClick={onSubmit}>Submit</Button>{' '}
            <Button variant="raised" className="btn-danger text-white" onClick={onCloseSupportPage}>Cancel</Button>
        </ModalFooter>
    </Modal>
);

export default SupportPage;
