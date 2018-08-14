/**
* Complaint View Page
*/
import React, { Component } from 'react';

import ComplaintViewItem from './components/ComplaintViewItem';

export default class ComplaintView extends Component {
	
	constructor(props) {
		super(props);
	}

	render() {
        const { match, } = this.props;
		return (
			<div className="report-wrapper">
				<div className="page-title d-flex justify-content-between align-items-center">
                    <div className="page-title-wrap">
                        <i className="ti-angle-left"></i>
                        <h2>Denúncias</h2>
                    </div>
                </div>

                <ComplaintViewItem />
                
			</div>
		);
	}
}
