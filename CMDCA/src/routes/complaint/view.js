/**
* Complaint View Page
*/
import React, { Component } from 'react';

import ComplaintViewItem from './components/ComplaintViewItem';

const user = {
	id: localStorage.getItem('user'),
	name: localStorage.getItem('user_name'),
	email: localStorage.getItem('user_email'),
	access_token: localStorage.getItem('token'),
	institution: localStorage.getItem('institution'),
	institutionType: localStorage.getItem('institution_type')
}

export default class ComplaintView extends Component {
	
	constructor(props) {
		super(props);
	}

	render() {
		let userParam = user;
		if(user.access_token != localStorage.getItem('access_token')){
			userParam = {
				id: localStorage.getItem('user'),
				name: localStorage.getItem('user_name'),
				email: localStorage.getItem('user_email'),
				access_token: localStorage.getItem('token'),
				institution: localStorage.getItem('institution'),
				institutionType: localStorage.getItem('institution_type'),
			}
		}
		const { match, } = this.props;
		return (
			<div className="report-wrapper">
				<div className="page-title d-flex justify-content-between align-items-center">
                    <div className="page-title-wrap">
                        <i className="ti-angle-left"></i>
                        <h2>Denúncias</h2>
                    </div>
                </div>

                <ComplaintViewItem {...this.props} id={this.props.match.params.id} user={userParam} />
                
			</div>
		);
	}
}
