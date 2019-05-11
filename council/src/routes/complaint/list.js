/**
* Report Page
*/
import React, { Component } from 'react';
import Button from '@material-ui/core/Button';
import IconButton from '@material-ui/core/IconButton';
import Hidden from '@material-ui/core/Hidden';
import { NavLink } from 'react-router-dom';
import Pagination from 'react-paginating';
import api from 'Api';
import buildParam from '../../util/Param';

import ComplaintListItem from './components/ComplaintListItem';
import RctSectionLoader from 'Components/RctSectionLoader/RctSectionLoader';

const user = {
    id: localStorage.getItem('user'),
    name: localStorage.getItem('user_name'),
    email: localStorage.getItem('user_email'),
    access_token: localStorage.getItem('token'),
    institution: localStorage.getItem('institution'),
    institutionType: localStorage.getItem('institution_type')
}

export default class ComplaintList extends Component {
	
	constructor(props) {
		super(props);
		this.state = {
            visible: true,
            complaints: [],
            currentPage: 0,
            perPage: 10,
            totalPages: 0,
            totalItens: 0,
            display: 7,
            filter: '',
            loader: true
		};
		this.onDismiss = this.onDismiss.bind(this);
    }
    
	onDismiss() {
		this.setState({ visible: false });
    }
    
    componentDidMount() {
        const { match } = this.props;
        let filter = match.url.substr(match.url.lastIndexOf('/') + 1);
        switch (filter) {
            case 'receive':
                this.filter('receive');
            break;
            case 'forward':
                this.filter('forward');
            break;
            case 'analysis':
                this.filter('analysis');
            break;
            case 'completed':
                this.filter('completed');
            break;
            default:
                this.loadItens();
                break;
        }
    }

    loadItens(){
        var param = {
            page : this.state.currentPage,
            filter: this.state.filter,
            institution: localStorage.getItem('institution')
        }
        api.get(`/v1/complaint?${buildParam(param)}`)
            .then(function(response){
                let data = response.data;
                let complaints = data.complaints;
                let complaintsWithStatus = [];
                let institution = user.institution;
                this.setState({
                    currentPage: data.pagination.currentPage,
                    perPage: data.pagination.perPage,
                    totalPages: data.pagination.totalPages,
                    totalItens: data.pagination.totalItens
                });

                if(complaints.length == 0){
                    this.setState({complaints: [], loader: false});
                    alert('Nenhuma denúncia encontrada!');
                }

                complaints.map((complaint, key) => {
                    let tempComplaint = {
                        id: complaint._id,
                        title: complaint._id,
                    };

                    let complaintStatus = complaint.status;
                    let forward = complaint.forwards[complaint.forwards.length -1];
                    tempComplaint.description = `${complaint.type_name} - Notificado em ${forward.date} no ${complaint.place_name}`
                    tempComplaint.typeName = complaint.type_name;
                    tempComplaint.placeName = complaint.place_name;
                    tempComplaint.forwardDate = forward.date;
                    tempComplaint._status = complaintStatus;

                    if(complaintStatus == '1'){
                        tempComplaint.status = 'blue';
                    }
                    else if(complaintStatus != '1' && complaintStatus != '9'){
                        if(institution == complaint.place){
                            tempComplaint.status = 'yellow';
                        }
                        else{
                            tempComplaint.status = 'secondary';
                        }
                    }
                    else{
                        if(complaintStatus == '9'){
                            tempComplaint.status = 'green';
                        }
                    }
                    complaintsWithStatus[key] = tempComplaint;
                });
                this.setState({complaints: complaintsWithStatus, loader: false});
            }.bind(this))
            .catch(function(error){
                this.setState({loader: false});
                switch (error.response.status) {
                    case 401:
                        alert('Sessão expirada');
                        this.props.history.push('/session/login');
                    break;
                    default:
                        console.log(error);
                    break;
                }
        }.bind(this));
    }
    
    handlePageChange = page => {
        this.setState({
          currentPage: page,
          complaints: []
        }, () => {
            this.loadItens();
        });
    };

    filter(type){
        this.setState({
            filter: type,
            currentPage: 0,
            complaints: [],
            loader: true
        }, () => {
            this.loadItens();
        });

    }

	render() {
        const { match } = this.props;
		return (
			<div className="report-wrapper">
				<div className="page-title d-flex justify-content-between align-items-center">
                    <div className="page-title-wrap">
                        <i className="ti-angle-left"></i>
                        <h2>Denúncias</h2>
                    </div>
                </div>
                    <Hidden mdDown>
                        <div className="mb-30">
                            <div className="row">
                                <div className="col-md-2">
                                    <Button component={NavLink} to={`${match.path.replace("list","")}insert`} variant="contained" className="btn-danger text-white btn-block font-weight-bold" >
                                        <i className="zmdi zmdi-plus-circle mr-10 font-lg"></i>
                                        Nova
                                    </Button>
                                </div>
                                <div className="col-md-2">
                                    <Button variant="contained" onClick={() => this.filter('receive')} className="btn-primary text-white btn-block font-weight-bold" >
                                        <i className="zmdi zmdi-assignment-returned mr-10 font-lg"></i>
                                        Recebidas
                                    </Button>
                                </div>
                                <div className="col-md-2">
                                    <Button variant="contained" onClick={() => this.filter('analysis')} className="btn-warning text-white btn-block font-weight-bold" >
                                        <i className="zmdi zmdi-search mr-10 font-lg"></i>
                                        Em análise
                                    </Button>
                                </div>
                                <div className="col-md-2">
                                    <Button variant="contained" onClick={() => this.filter('forward')} className="btn-secondary text-white btn-block font-weight-bold" >
                                        <i className="zmdi zmdi-mail-reply-all mr-10 font-lg"></i>
                                        Encaminhadas
                                    </Button>
                                </div>
                                <div className="col-md-2">
                                    <Button variant="contained" onClick={() => this.filter('completed')} className="btn-success text-white btn-block font-weight-bold" >
                                        <i className="zmdi zmdi-tab mr-10 font-lg"></i>
                                        Concluídas
                                    </Button>
                                </div>
                            </div>
                        </div>
                    </Hidden>
                    <Hidden smUp>
                        <IconButton component={NavLink} to={`${match.path.replace("list","")}insert`} className="text-danger" aria-label="Nova">
                            <i className="zmdi zmdi-plus-circle"></i>
                        </IconButton>
                        <IconButton className="label-blue" onClick={() => this.filter('receive')} aria-label="Recebidas">
                            <i className="zmdi zmdi-assignment-returned"></i>
                        </IconButton>
                        <IconButton className="label-yellow" onClick={() => this.filter('analysis')} aria-label="Em análise">
                            <i className="zmdi zmdi-search"></i>
                        </IconButton>
                        <IconButton className="label-grey" onClick={() => this.filter('forwards')} aria-label="Encaminhadas">
                            <i className="zmdi zmdi-mail-reply-all"></i>
                        </IconButton>
                        <IconButton className="label-green" onClick={() => this.filter('completed')} aria-label="Concluídas">
                            <i className="zmdi zmdi-tab"></i>
                        </IconButton>
                    </Hidden>
                {this.state.loader && <RctSectionLoader />} 
                {this.state.complaints.length > 0 && <ComplaintListItem {...this.props} listItems={this.state.complaints} />}
                {this.state.complaints.length > 0 &&
                <div className="mb-20">
                    <Pagination
                        total={this.state.totalItens}
                        limit={this.state.perPage}
                        pageCount={this.state.totalPages}
                        currentPage={this.state.currentPage}
                    >
                    {({
                        pages,
                        currentPage,
                        hasNextPage,
                        hasPreviousPage,
                        previousPage,
                        nextPage,
                        totalPages,
                        getPageItemProps
                    }) => (
                        <div>
                        <button className="button-pagination"
                            {...getPageItemProps({
                            pageValue: 1,
                            onPageChange: this.handlePageChange
                            })}
                        >
                            primeira
                        </button>

                        {hasPreviousPage && (
                            <button
                            {...getPageItemProps({
                                pageValue: previousPage,
                                onPageChange: this.handlePageChange
                            })}
                            >
                            {'<'}
                            </button>
                        )}

                        {pages.map(page => {
                            let activePage = null;
                            if (currentPage === page) {
                            activePage = { backgroundColor: '#fdce09' };
                            }
                            return (
                            <button className="button-pagination"
                                key={page}
                                style={activePage}
                                {...getPageItemProps({
                                pageValue: page,
                                onPageChange: this.handlePageChange
                                })}
                            >
                                {page}
                            </button>
                            );
                        })}

                        {hasNextPage && (
                            <button className="button-pagination"
                            {...getPageItemProps({
                                pageValue: nextPage,
                                onPageChange: this.handlePageChange
                            })}
                            >
                            {'>'}
                            </button>
                        )}

                        <button className="button-pagination"
                            {...getPageItemProps({
                            pageValue: totalPages,
                            onPageChange: this.handlePageChange
                            })}
                        >
                            última
                        </button>
                        </div>
                    )}
                    </Pagination>
			    </div>}
			</div>
		);
	}
}
