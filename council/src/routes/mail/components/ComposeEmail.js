/**
 * Compose Email Component
 */
import React, { Component } from 'react';
import { InputGroup, InputGroupAddon, Input, InputGroupText } from 'reactstrap';
import ReactQuill from 'react-quill';
import Button from '@material-ui/core/Button';
import Icon from '@material-ui/core/Icon';
import { connect } from 'react-redux';

// actions
import { sendEmail, emailSentSuccessfully } from 'Actions';

// intl message
import IntlMessages from 'Util/IntlMessages';

// modules
const modules = {
    toolbar: [
        [{ 'header': [1, 2, 3, 4, 5, 6, false] }],
        [{ 'font': [] }],
        ['bold', 'italic', 'underline', 'strike', 'blockquote'],
        [{ 'list': 'ordered' }, { 'list': 'bullet' }, { 'indent': '-1' }, { 'indent': '+1' }],
        ['link', 'image'],
        ['clean'],
        [{ 'align': [] }]
    ],
};

// formats
const formats = [
    'header',
    'font',
    'bold', 'italic', 'underline', 'strike', 'blockquote',
    'list', 'bullet', 'indent',
    'link', 'image', 'align'
];

class ComposeEmail extends Component {

    state = {
        to: '',
        cc: '',
        bcc: '',
        subject: '',
        message: ''
    }

    /**
     * On Send Email
     */
    onSendEmail() {
        const { history } = this.props;
        const { to, subject, message } = this.state;
        if (to !== '' && subject !== '' && message !== '') {
            this.props.sendEmail();
            history.push('/app/mail/folder/sent');
            setTimeout(() => {
                this.props.emailSentSuccessfully();
            }, 2000);
        }
    }

    /**
     * On Change Form Values
     */
    onChangeFormValue(key, value) {
        this.setState({ [key]: value });
    }

    render() {
        const { to, cc, bcc, subject, message } = this.state;
        return (
            <div className="compose-email-container">
                <InputGroup>
                    <InputGroupAddon addontype="prepend">
                        <InputGroupText>To</InputGroupText>
                    </InputGroupAddon>
                    <Input
                        name="to"
                        type="email"
                        value={to}
                        onChange={(e) => this.onChangeFormValue('to', e.target.value)}
                    />
                </InputGroup>
                <InputGroup>
                    <InputGroupAddon addontype="prepend">
                        <InputGroupText>CC</InputGroupText>
                    </InputGroupAddon>
                    <Input
                        name="cc"
                        type="email"
                        value={cc}
                        onChange={(e) => this.onChangeFormValue('cc', e.target.value)}
                    />
                </InputGroup>
                <InputGroup>
                    <InputGroupAddon addontype="prepend">
                        <InputGroupText>BCC</InputGroupText>
                    </InputGroupAddon>
                    <Input
                        name="bcc"
                        type="email"
                        value={bcc}
                        onChange={(e) => this.onChangeFormValue('bcc', e.target.value)}
                    />
                </InputGroup>
                <InputGroup>
                    <InputGroupAddon addontype="prepend">
                        <InputGroupText>Subject</InputGroupText>
                    </InputGroupAddon>
                    <Input
                        name="subject"
                        type="text"
                        value={subject}
                        onChange={(e) => this.onChangeFormValue('subject', e.target.value)}
                    />
                </InputGroup>
                <ReactQuill
                    modules={modules}
                    formats={formats}
                    value={message}
                    onChange={(value) => this.onChangeFormValue('message', value)}
                />
                <div className="compose-email-actions p-10">
                    <Button className="btn-primary text-white" onClick={() => this.onSendEmail()}>
                        <Icon className="mr-10">send</Icon>
                        <IntlMessages id="widgets.send" />
                    </Button>
                </div>
            </div>
        );
    }
}

export default connect(null, {
    sendEmail,
    emailSentSuccessfully
})(ComposeEmail);
