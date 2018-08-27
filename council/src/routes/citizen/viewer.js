/**
 * Citizen view
 */
import React, { Component } from 'react';
import SimpleBar from 'Components/NavBar/SimpleBar';
import ComplaintViewItem from 'Routes/complaint/components/ComplaintViewItem';

export default class View extends Component {

	constructor(props){
		super(props);
		this.state = {
			processId: 0
		};
    }

	render() {
		return (
			<div style={{overflowY: 'auto', height: "100%"}}>
				<SimpleBar />
					<div className="session-inner-wrapper mt-40">
						<div className="container">
							<div className="row justify-content-md-center align-items-center">
								<h2 className="mb-20"> Andamento do processo </h2>
								<div className="col-sm-5 col-md-5 col-lg-10">
									<ComplaintViewItem citizen={true} />
								</div>
							</div>
						</div>
					</div>
			</div>
		);
	}
}
