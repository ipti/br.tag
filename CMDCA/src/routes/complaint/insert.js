
import React, { Component } from 'react';
import ComplaintForm from './components/ComplaintForm';


export default class ComplaintInsert extends Component {
	constructor(props) {
		super(props);
	}

	render() {
		return (
			<div className="complaint-wrapper">
                <ComplaintForm action="create"/>
			</div>
		);
	}
}
