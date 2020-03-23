
import React, { Component } from 'react';
import ComplaintForm from './components/ComplaintForm';


export default class ComplaintFormalize extends Component {
	constructor(props) {
		super(props);
	}

	render() {
		return (
			<div className="complaint-wrapper">
                <ComplaintForm {...this.props} id={this.props.match.params.id} action="formalize"/>
			</div>
		);
	}
}
