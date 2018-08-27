/**
 * TermsCondition Page
 */
import React, { Component } from 'react';
import IconButton from '@material-ui/core/IconButton';
import { Link } from 'react-router-dom';
import SimpleBar from 'Components/NavBar/SimpleBar';
import Button from '@material-ui/core/Button';
import { Form, FormGroup, Input } from 'reactstrap';

export default class Follow extends Component {

	constructor(props){
		super(props);
		this.state = {
			processId: 0
		};
	}

	consultar(){

	}

	render() {
		return (
			<div>
				<SimpleBar />
				<div className="session-inner-wrapper">
					<div className="container" style={{height:"600px", maxHeight:"100%"}}>
						<div className="row justify-content-md-center align-items-center h-100">
							<div className="col-sm-5 col-md-5 col-lg-6">
							<div className="session-body text-center">
								<div className="session-head mb-30">
								<h2 className="font-weight-bold">Acompanhamento de processo</h2>
								<p className="mb-0">Informe o número do processo no campo abaixo</p>
								</div>
								<Form>
								<FormGroup className="has-wrapper">
									<Input type="text" name="processId" id="processId" className="has-input input-lg" placeholder="Nº do processo" onChange={(event) => this.setState({ processId: event.target.value })} />
									<span className="has-icon"><i className="ti-file"></i></span>
								</FormGroup>
								<FormGroup className="mb-15">
									<Button
									color="primary"
									className="btn-block text-white w-100"
									variant="raised"
									size="large"
									onClick={() => this.consultar()}>
									Consultar
									</Button>
								</FormGroup>
								</Form>
							</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		);
	}
}
