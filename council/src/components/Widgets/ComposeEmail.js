/**
 * Compose Email Widget
 */
import React, { Component, Fragment } from 'react';
import ReactQuill from 'react-quill';
import { Col, Form, Label, Input, } from 'reactstrap';
import { NotificationManager } from 'react-notifications';
import 'react-quill/dist/quill.snow.css';

// rct card box
import { RctCardFooter } from 'Components/RctCard';

// intl messages
import IntlMessages from 'Util/IntlMessages';

const modules = {
	toolbar: [
		[{ 'header': [1, 2, 3, 4, 5, 6, false] }],
		[{ 'font': [] }],
		['bold', 'italic', 'underline', 'strike', 'blockquote'],
		[{ 'list': 'ordered' }, { 'list': 'bullet' }, { 'indent': '-1' }, { 'indent': '+1' }],
		['link', 'image'],
		['clean'],
		[{ 'align': [] }],
		['code-block']
	],
};

const formats = [
	'header',
	'font',
	'bold', 'italic', 'underline', 'strike', 'blockquote',
	'list', 'bullet', 'indent',
	'link', 'image', 'align',
	'code-block'
];

class ComposeEmailWidget extends Component {

	state = {
		text: ''
	}

	// on click send
	onClickSend() {
		this.setState({ composeEmailReload: true });
		setTimeout(() => {
			this.setState({ composeEmailReload: false });
			NotificationManager.success('Email has been sent successfully!');
		}, 3000);
	}

	// on click save to draft
	onClickSaveToDraft() {
		this.setState({ composeEmailReload: true });
		setTimeout(() => {
			this.setState({ composeEmailReload: false });
			NotificationManager.success('Email Saved!');
		}, 3000);
	}

	render() {
		return (
			<Fragment>
				<Form className="editor">
					<div className="form-wrap row no-gutters">
						<Label for="exampleEmail" sm={2}>To :</Label>
						<Col sm={10}>
							<Input type="email" name="email" id="exampleEmail" />
						</Col>
					</div>
					<div className="form-wrap row no-gutters">
						<Label for="exampleEmail" sm={2}>Subject :</Label>
						<Col sm={10}>
							<Input type="text" name="text" id="exampleText" />
						</Col>
					</div>
				</Form>
				<ReactQuill modules={modules} formats={formats} placeholder="Enter Your Message.." />
				<RctCardFooter>
					<a href="javascript:void(0)" onClick={() => this.onClickSend()} className="btn btn-success btn-sm mr-10">
						<IntlMessages id="widgets.send" />
					</a>
					<a href="javascript:void(0)" onClick={() => this.onClickSaveToDraft()} className="btn btn-secondary btn-sm mr-10">
						<IntlMessages id="widgets.saveAsDrafts" />
					</a>
				</RctCardFooter>
			</Fragment>
		);
	}
}

export default ComposeEmailWidget;
