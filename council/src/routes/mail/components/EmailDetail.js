/**
 * Email Detail
 */
import React, { Component } from 'react';
import { connect } from 'react-redux';
import { withRouter } from 'react-router-dom';
import CircularProgress from '@material-ui/core/CircularProgress';
import IconButton from '@material-ui/core/IconButton';
import ArrowBackIcon from '@material-ui/icons/ArrowBack';
import Avatar from '@material-ui/core/Avatar';
import classnames from 'classnames';

// redux actions
import { hideLoadingIndicator, onNavigateToEmailListing, onDeleteEmail } from 'Actions';

//Intl Message
import IntlMessages from 'Util/IntlMessages';

// rct section loader
import RctSectionLoader from 'Components/RctSectionLoader/RctSectionLoader';

class EmailDetail extends Component {

	componentWillMount() {
		this.getEmailDetails();
	}

	/**
	 * Get Email Details By Id
	 */
	getEmailDetails() {
		let self = this;
		setTimeout(() => {
			this.props.hideLoadingIndicator();
		}, 1500);
	}

	/**
	 * On Back Press Naviagte To Email Listing Page
	 */
	onBackPress() {
		const { history } = this.props;
		this.props.onNavigateToEmailListing();
		history.goBack();
	}

	/**
	 * On Delete Email
	 */
	onDeleteEmail() {
		const { history } = this.props;
		this.props.onDeleteEmail();
		history.goBack();
	}

	/**
	 * Function to return task label name
	 */
	getTaskLabelNames = (taskLabels) => {
		let elements = [];
		const { labels } = this.props;
		for (const taskLabel of taskLabels) {
			for (const label of labels) {
				if (label.value === taskLabel) {
					let ele = <span key={label.value} className={classnames("badge badge-pill mx-5 mb-5 mb-md-0", { 'badge-success': label.value === 1, 'badge-primary': label.value === 2, 'badge-info': label.value === 3, 'badge-danger': label.value === 4 })}><IntlMessages id={label.name} /></span>;
					elements.push(ele);
				}
			}
		}
		return elements;
	}

	render() {
		const { currentEmail, loading } = this.props;
		if (loading) {
			return (
				<RctSectionLoader />
			)
		}
		return (
			<div className="email-detail-page-warrper col-md-12">
				<div className="top-head border-bottom-0 d-flex justify-content-between">
					<IconButton onClick={() => this.onBackPress()}>
					</IconButton>
					<div className="mail-action">
						<IconButton>
							<i className="zmdi zmdi-mail-reply"></i>
						</IconButton>
						<IconButton>
							<i className="zmdi zmdi-print"></i>
						</IconButton>
						<IconButton onClick={() => this.onDeleteEmail()}>
							<i className="zmdi zmdi-delete"></i>
						</IconButton>
					</div>
				</div>
				
					<div>
						<div className="top-head d-flex justify-content-between align-items-center">
							<h4 className="mb-0 text-capitalize w-75 d-flex align-items-center">
								<IconButton>
									<i className="zmdi zmdi-star-outline mr-20"></i>
								</IconButton>
								<span>Processo nº 20180816-23</span>
							</h4>
							
						</div>
						<div className="user-detail d-flex justify-content-between border-0">
							<div className="media w-80">
								
								<Avatar className="mr-20">LA</Avatar>
								<div className="media-body">
									<h5>Lais Souza</h5>
									<p className="mb-0">Notificado em<span className="text-muted font-sm"> 12/08/2018</span></p>
									<p className="mb-0">Local: <span className="text-muted font-sm">Conselho Tutelar</span></p>
								</div>
							</div>
						</div>
						<div className="mail-detail pl-lg-70 pt-0">
							<div className="mb-20 pl-lg-15">
								<p>De acordo com o Conselho Tutelar, a mãe da criança tem 37 anos e é formada em direito e administração de empresas. O pai do menino, que não mora com a família, vive em Camaquã, na região sul do Rio Grande do Sul, e foi avisado do caso para que tomasse providências. O menino estuda em uma escola particular de Porto Alegre.

O estudante de doutorado em estudos estratégicos internacionais Robson Valdez, de 38 anos, afirma que estava saindo do restaurante, localizado na Rua Almirante Gonçalves, quando cruzou com a criança, a mãe e a avó, que entravam no estabelecimento. Ele relata que o menino tropeçou na mulher mais velha e foi repreendido pela mãe, que falava ao celular ao mesmo tempo. "Como qualquer outra criança, ele estava bastante distraído. Ela simplesmente deu um tapa tão forte no rosto da criança que foi ouvido até por um vigia do outro lado da rua", conta.</p>
							</div>
						</div>
						<div className="attachments p-20 border-bottom">
							<div className="d-flex justify-content-between">
								<h4>3 Anexos</h4>
								<div className="mail-action">
									<IconButton>
										<i className="zmdi zmdi-file"></i>
									</IconButton>
									<IconButton>
										<i className="zmdi zmdi-cloud-download"></i>
									</IconButton>
								</div>
							</div>
							<ul className="list-inline">
								<li className="list-inline-item border-bottom-0">
									<img src={require('Assets/img/dsc04499.jpg')} alt="attachments" className="img-fluid mb-10" width="180" height="140" />
									<p className="mb-5">Attachment 1.jpg</p>
									<div className="list-action">
										<a href="javascript:void(0)"><i className="zmdi zmdi-download mr-10"></i></a>
										<a href="javascript:void(0)"><i className="zmdi zmdi-zoom-in"></i></a>
									</div>
								</li>
								<li className="list-inline-item border-bottom-0">
									<img src={require('Assets/img/dsc04512.jpg')} alt="attachments" className="img-fluid mb-10" width="180" height="140" />
									<p className="mb-5">Attachment 2.jpg</p>
									<div className="list-action">
										<a href="javascript:void(0)"><i className="zmdi zmdi-download mr-10"></i></a>
										<a href="javascript:void(0)"><i className="zmdi zmdi-zoom-in"></i></a>
									</div>
								</li>
								<li className="list-inline-item border-bottom-0">
									<img src={require('Assets/img/about-card-3.png')} alt="attachments" className="img-fluid mb-10" width="180" height="140" />
									<p className="mb-5">Attachment 3.jpg</p>
									<div className="list-action">
										<a href="javascript:void(0)"><i className="zmdi zmdi-download mr-10"></i></a>
										<a href="javascript:void(0)"><i className="zmdi zmdi-zoom-in"></i></a>
									</div>
								</li>
							</ul>
						</div>
						
					</div>
			
			</div>
		);
	}
}

// map state to props
const mapStateToProps = ({ emailApp }) => {
	return emailApp;
};

export default withRouter(connect(mapStateToProps, {
	hideLoadingIndicator,
	onNavigateToEmailListing,
	onDeleteEmail
})(EmailDetail));
