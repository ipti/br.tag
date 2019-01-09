/**
* Complaint View Page
*/
import React, { Component } from 'react';

import ComplaintViewItem from './components/ComplaintViewItem';

const user = {
	id: sessionStorage.getItem('user'),
	name: sessionStorage.getItem('user_name'),
	email: sessionStorage.getItem('user_email'),
	access_token: sessionStorage.getItem('token'),
	institution: sessionStorage.getItem('institution'),
	institutionType: sessionStorage.getItem('institution_type')
}

export default class ComplaintView extends Component {
	
	constructor(props) {
		super(props);
	}

	render() {
		let userParam = user;
		if(user.access_token != sessionStorage.getItem('access_token')){
			userParam = {
				id: sessionStorage.getItem('user'),
				name: sessionStorage.getItem('user_name'),
				email: sessionStorage.getItem('user_email'),
				access_token: sessionStorage.getItem('token'),
				institution: sessionStorage.getItem('institution'),
				institutionType: sessionStorage.getItem('institution_type'),
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
