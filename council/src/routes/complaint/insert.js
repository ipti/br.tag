/**
* Report Page
*/
import React, { Component } from 'react';

// page title bar
import PageTitleBar from 'Components/PageTitleBar/PageTitleBar';

// intl messages
import IntlMessages from 'Util/IntlMessages';

import ComplaintForm from './components/ComplaintForm';


export default class ComplaintInsert extends Component {
	constructor(props) {
		super(props);
	}

	render() {
		return (
			<div className="complaint-wrapper">
                <ComplaintForm />
			</div>
		);
	}
}
