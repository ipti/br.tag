/**
 * Citizen view
 */
import React, { Component } from 'react';
import SimpleBar from 'Components/NavBar/SimpleBar';
import ComplaintViewItem from 'Routes/complaint/components/ComplaintViewItem';
import config from '../../constants/AppConfig';

export default class View extends Component {

	constructor(props){
		super(props);
		this.state = {
			processId: 0
		};
    }

	render() {
		return (
			<div style={{overflowY: 'auto', height: "100%", minHeight: '400px'}}>
				<SimpleBar />
					<div className="session-inner-wrapper mt-40">
						<div className="container" >
							<div className="row justify-content-md-center align-items-center">
								<h2 className="mb-20"> Andamento do processo </h2>
								<div className="col-sm-5 col-md-5 col-lg-10" style={{minHeight: '400px'}}>
									<ComplaintViewItem citizen={true} id={this.props.match.params.id} user={config.citizen} />
								</div>
							</div>
						</div>
					</div>
			</div>
		);
	}
}
