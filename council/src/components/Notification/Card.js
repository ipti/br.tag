import React, { Component } from 'react';
import PropTypes from 'prop-types';

class Card extends Component{

    render(){
        const {notified, createdAt} = this.props;
        return(
            <div className="col-sm-12 col-md-4 cursor-pointer" >
                <div className="rct-block">
                    <div className="rct-block-content">
                        <div className="row mx-0">
                            <div className="col-7 d-flex aling-items-center">
                                <div className="mr-2">
                                    <img width="26px" src={require('../../assets/img/icons/student.png')} />
                                </div>
                                <div className="w-100">
                                    <h4 className="notification-card-title text-ellipsis">{notified.name}</h4>
                                    <span className="notification-card-subtitle">Nome</span>
                                </div>
                            </div>
                            <div className="col-4 d-flex aling-items-center">
                                <div className="mr-2">
                                    <img width="26px" src={require('../../assets/img/icons/calendar.png')} />
                                </div>
                                <div>
                                    <h4 className="notification-card-title">{createdAt.split(' ')[0]}</h4>
                                    <span className="notification-card-subtitle">Criada em</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        )
    }
}

Card.propTypes = {
    notified: PropTypes.string.isRequired,
    createdAt: PropTypes.string.isRequired,
};


export default Card;