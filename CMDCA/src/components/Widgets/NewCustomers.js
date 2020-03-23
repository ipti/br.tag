/**
 * New Customers Widget
 */
import React, { Component, Fragment } from 'react';
import update from 'react-addons-update';
import {
	Modal,
	ModalHeader,
	ModalBody,
	ModalFooter,
	Form,
	FormGroup,
	Label,
	Input
} from 'reactstrap';
import Button from '@material-ui/core/Button';
import Snackbar from '@material-ui/core/Snackbar';
import Avatar from '@material-ui/core/Avatar';
import { Scrollbars } from 'react-custom-scrollbars';

// api
import api from 'Api';

// intl messages
import IntlMessages from 'Util/IntlMessages';

// rct section loader
import RctSectionLoader from 'Components/RctSectionLoader/RctSectionLoader';

import DeleteConfirmationDialog from 'Components/DeleteConfirmationDialog/DeleteConfirmationDialog';

class NewCustomers extends Component {

	state = {
		sectionReload: false,
		newCustomers: null,
		selectedDeletedCustomer: null,
		editCustomerModal: false,
		editCustomer: null,
		snackbar: false,
		successMessage: '',
		addNewCustomerForm: false,
		addNewCustomerDetails: {
			customer_email: '',
			customer_name: '',
			id: '',
			photo_url: ''
		}
	}

	componentDidMount() {
		this.getNewCustomers();
	}

	// get new customers
	getNewCustomers() {
		this.setState({ sectionReload: true });
		api.get('newCustomers.js')
			.then((response) => {
				this.setState({ newCustomers: response.data, sectionReload: false });
			})
			.catch(error => {
				this.setState({ newCustomers: null, sectionReload: false });
			})
	}

	// on delete customer
	onDeleteCustomer(customer) {
		this.refs.deleteConfirmationDialog.open();
		this.setState({ selectedDeletedCustomer: customer });
	}

	// delete customer
	deleteCustomer() {
		this.refs.deleteConfirmationDialog.close();
		this.setState({ sectionReload: true });
		let newCustomers = this.state.newCustomers;
		let index = newCustomers.indexOf(this.state.selectedDeletedCustomer);
		setTimeout(() => {
			newCustomers.splice(index, 1);
			this.setState({
				newCustomers,
				snackbar: true,
				successMessage: 'Customer Deleted Successfully',
				sectionReload: false
			});
		}, 1500);
	}

	// edit customer
	onEditCustomer(customer) {
		this.setState({ editCustomerModal: true, editCustomer: customer, addNewCustomerForm: false });
	}

	// toggle edit customer modal
	toggleEditCustomerModal = () => {
		this.setState({
			editCustomerModal: !this.state.editCustomerModal
		});
	}

	// submit customer edit form
	onSubmitCustomerEditDetailForm() {
		const { editCustomer, newCustomers } = this.state;
		if (editCustomer.customer_name !== '' && editCustomer.customer_email !== '') {
			this.setState({
				editCustomerModal: false,
				sectionReload: true
			});
			let indexOfCustomer;
			for (let i = 0; i < newCustomers.length; i++) {
				const customer = newCustomers[i];
				if (customer.customer_id === editCustomer.customer_id) {
					indexOfCustomer = i;
				}
			}
			let self = this;
			setTimeout(() => {
				self.setState({ sectionReload: false, snackbar: true, successMessage: 'Customer Updated Success' });
				self.setState({
					newCustomers: update(newCustomers,
						{
							[indexOfCustomer]: { $set: editCustomer }
						}
					)
				});
			}, 1500);
		}
	}

	// on change customer details
	onChangeCustomerDetails(key, value) {
		this.setState({
			editCustomer: {
				...this.state.editCustomer,
				[key]: value
			}
		});
	}

	// add new customer
	addNewCustomer() {
		this.setState({
			editCustomerModal: true,
			addNewCustomerForm: true,
			editCustomer: null,
			addNewCustomerDetails: {
				customer_email: '',
				customer_name: '',
				id: '',
				photo_url: ''
			}
		});
	}

	// on change customer add new form value
	onChangeCustomerAddNewForm(key, value) {
		this.setState({
			addNewCustomerDetails: {
				...this.state.addNewCustomerDetails,
				[key]: value
			}
		})
	}

	// on submit add new customer form
	onSubmitAddNewCustomerForm() {
		const { addNewCustomerDetails } = this.state;
		if (addNewCustomerDetails.customer_name !== '' && addNewCustomerDetails.customer_email !== '') {
			this.setState({ editCustomerModal: false, sectionReload: true });
			let newCustomer = addNewCustomerDetails
			newCustomer.id = new Date().getTime(),
				newCustomer.photo_url = '';
			let newCustomers = this.state.newCustomers;
			let self = this;
			setTimeout(() => {
				newCustomers.push(newCustomer);
				self.setState({
					newCustomers,
					sectionReload: false,
					snackbar: true,
					successMessage: 'Customer Added Successfully'
				});
			}, 1500);
		}
	}

	render() {
		const { newCustomers, sectionReload, editCustomerModal, addNewCustomerForm, editCustomer, snackbar, successMessage, addNewCustomerDetails } = this.state;
		return (
			<Fragment>
				{sectionReload &&
					<RctSectionLoader />
				}
				<Scrollbars className="rct-scroll" autoHeight autoHeightMin={100} autoHeightMax={290} autoHide>
					<ul className="list-group new-customer-list">
						{newCustomers && newCustomers.map((customer, key) => (
							<li className="list-group-item d-flex justify-content-between" key={key}>
								<div className="d-flex align-items-start">
									<div className="media">
										<div className="media-left mr-15">
											{customer.photo_url === '' ?
												<Avatar>{customer.customer_name.charAt(0)}</Avatar>
												: <img src={customer.photo_url} alt="project logo" className="media-object rounded-circle" width="40" height="40" />
											}
										</div>
										<div className="media-body">
											<span className="d-block fs-14">{customer.customer_name}</span>
											<span className="d-block fs-12 text-muted">{customer.customer_email}</span>
										</div>
									</div>
								</div>
								<div className="d-flex align-items-end">
									<a href="javascript:void(0)" color="primary" onClick={() => this.onEditCustomer(customer)}>
										<i className="zmdi zmdi-edit"></i>
									</a>
									<a href="javascript:void(0)" className="text-danger" onClick={() => this.onDeleteCustomer(customer)}>
										<i className="zmdi zmdi-close"></i>
									</a>
								</div>
							</li>
						))}
					</ul>
				</Scrollbars>
				<div className="d-flex p-3">
					<Button variant="raised" color="primary" className="text-white" onClick={() => this.addNewCustomer()}><IntlMessages id="widgets.addNew" /></Button>
				</div>
				{/* Delete Customer Confirmation Dialog */}
				<DeleteConfirmationDialog
					ref="deleteConfirmationDialog"
					title="Are You Sure Want To Delete?"
					message="Are You Sure Want To Delete Permanently This Customer."
					onConfirm={() => this.deleteCustomer()}
				/>
				{/* Customer Edit Modal*/}
				{editCustomerModal &&
					<Modal
						isOpen={editCustomerModal}
						toggle={this.toggleEditCustomerModal}
					>
						<ModalHeader toggle={this.toggleEditCustomerModal}>
							{addNewCustomerForm ? 'Add New Customer' : 'Edit Customer'}
						</ModalHeader>
						<ModalBody>
							{addNewCustomerForm ?
								<Form>
									<FormGroup>
										<Label for="customerName">Name</Label>
										<Input
											type="text"
											name="name"
											id="customerName"
											value={addNewCustomerDetails.customer_name}
											onChange={(e) => this.onChangeCustomerAddNewForm('customer_name', e.target.value)}
										/>
									</FormGroup>
									<FormGroup>
										<Label for="customerEmail">Email</Label>
										<Input
											type="email"
											name="email"
											id="customerEmail"
											value={addNewCustomerDetails.customer_email}
											onChange={(e) => this.onChangeCustomerAddNewForm('customer_email', e.target.value)}
										/>
									</FormGroup>
								</Form>
								: <Form>
									<FormGroup>
										<Label for="customerId">Id</Label>
										<Input
											type="text"
											name="name"
											id="customerId"
											defaultValue={editCustomer.customer_id}
											readOnly
										/>
									</FormGroup>
									<FormGroup>
										<Label for="customerName">Name</Label>
										<Input
											type="text"
											name="name"
											id="customerName"
											value={editCustomer.customer_name}
											onChange={(e) => this.onChangeCustomerDetails('customer_name', e.target.value)}
										/>
									</FormGroup>
									<FormGroup>
										<Label for="customerEmail">Email</Label>
										<Input
											type="email"
											name="email"
											id="customerEmail"
											value={editCustomer.customer_email}
											onChange={(e) => this.onChangeCustomerDetails('customer_email', e.target.value)}
										/>
									</FormGroup>
								</Form>
							}
						</ModalBody>
						<ModalFooter>
							{addNewCustomerForm ?
								<div>
									<Button variant="raised" color="primary" className="text-white" onClick={() => this.onSubmitAddNewCustomerForm()}><IntlMessages id="button.add" /></Button>{' '}
									<Button variant="raised" className="btn-danger text-white" onClick={this.toggleEditCustomerModal}><IntlMessages id="button.cancel" /></Button>
								</div>
								: <div><Button variant="raised" color="primary" className="text-white" onClick={() => this.onSubmitCustomerEditDetailForm()}><IntlMessages id="button.update" /></Button>{' '}
									<Button variant="raised" className="btn-danger text-white" onClick={this.toggleEditCustomerModal}><IntlMessages id="button.cancel" /></Button></div>
							}
						</ModalFooter>
					</Modal>
				}
				<Snackbar
					anchorOrigin={{
						vertical: 'top',
						horizontal: 'center',
					}}
					open={snackbar}
					onClose={() => this.setState({ snackbar: false })}
					autoHideDuration={2000}
					message={<span id="message-id">{successMessage}</span>}
				/>
			</Fragment>
		);
	}
}

export default NewCustomers;
