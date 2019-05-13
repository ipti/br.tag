import React, { Component } from 'react';
import PropTypes from 'prop-types';
import renderHTML from 'react-render-html';;
import { Button } from 'reactstrap'
import { connect } from 'react-redux';
import { previewWarning, deleteWarning, getWarning } from 'Actions';

class Card extends Component {

    constructor(props) {
        super(props);
        this.state = {
            showActions: false
        }
    }

    handleClick = () => {
        this.setState({ showActions: !this.state.showActions });
    };

    handleClose = () => {
        this.setState({ showActions: false });
    };

    deleteWarning = (id) => {
        this.props.deleteWarning(id, () => {
            this.props.getWarning();
        });
    }

    render(){
        const {id, personAdolescentName, reason, createdAt} = this.props;
        return(
            <div className="col-sm-12 col-md-4 cursor-pointer" >
                <div className="rct-block">
                    <div className="rct-block-content">
                        {
                            this.state.showActions ? (
                                <div className="row mx-0 w-100" onClick={this.handleClose}>
                                    <div className="col-12 d-flex justify-content-center aling-items-center">
                                        <Button className="mr-2" color="primary" onClick={() => this.props.previewWarning(id)} >Visualizar</Button>
                                        <Button color="danger" onClick={() => this.deleteWarning(id)} >Excluir</Button>
                                    </div>
                                </div>
                            ) : (
                                <div onClick={this.handleClick}>
                                    <div className="row mx-0">
                                        <div className="col-7 d-flex aling-items-center">
                                            <div className="mr-2">
                                                <img width="26px" src={require('../../assets/img/icons/student.png')} />
                                            </div>
                                            <div className="w-100">
                                                <span className="warning-card-subtitle">Adolescente</span>
                                                <h4 className="warning-card-title text-ellipsis">{personAdolescentName}</h4>
                                            </div>
                                        </div>
                                        <div className="col-4 d-flex aling-items-center">
                                            <div className="mr-2">
                                                <img width="26px" src={require('../../assets/img/icons/calendar.png')} />
                                            </div>
                                            <div>
                                                <h4 className="warning-card-title">{createdAt.split(' ')[0]}</h4>
                                                <span className="warning-card-subtitle">Criada em</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            )
                        }
                    </div>
                </div>
            </div>
        )
    }
}

Card.propTypes = {
    personAdolescentName: PropTypes.string.isRequired,
    createdAt: PropTypes.string.isRequired,
};

const mapStateToProps = ({ warning }) => {
    return warning;
 };

export default connect(mapStateToProps, {
    previewWarning: previewWarning, 
    deleteWarning: deleteWarning,
    getWarning: getWarning,
 })(Card);