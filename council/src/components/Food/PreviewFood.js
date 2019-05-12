import React, { Component, Fragment } from 'react';
import PreviewDocument from 'Components/PreviewDocument/PreviewDocument'
import * as Head from 'Components/PreviewDocument/Header'
import PropTypes from 'prop-types';

const Notified = props =>{
    return(
        <Fragment>
            <div className="d-flex">
                <h4 className="title-header">Nome: {props.name}</h4>
                <h4 className="title-header">Endereço: {props.street}</h4>
                <h4 className="title-header">Cidade: {props.city}</h4>
            </div>
        </Fragment>
    );
}

const ParagrahpOne = props =>{
    return(
        <div className="d-flex">
            <p className="text-justify">
                O conselho Tutelar dos Diretos da Criança e do Adolescente, órgão permanente e autônomo, não jurisdicional, encarregado pela sociedade de zelar pelos direitos da criança e do adolescente definidos na Lei Federal do ECA (art.131-nº8.069/90), Vem mui respeitosamente notificar a senhor(a) para comparecer na sede do Conselho Tutelar nesta, no <strong>Dia {props.date}</strong> ás <strong>{props.time}</strong>
                Para tratar de assuntos de seu interesse.
            </p>
        </div>
    );
}

const ParagrahpTwo = props =>{
    return(
        <div className="d-flex">
            <p className="text-justify">
                Agradecemos a atenção e lembramos que o não comparecimento injustificado da presente, poderá implicar em medidas judiciais, inclusive condução coercitiva, sem prejuízo de eventual responsabilização por crime ou infração administrativa (art.236 e 249- ECA).
            </p>
        </div>
    );
}

const ParagrahpThree = props =>{
    return(
        <div className="d-flex">
            <p className="text-left">
                Atenciosamente,
            </p>
            <p className="text-left">
                Conselho Tutelar de Santa Luzia do Itanhi/Se {props.date}
            </p>
        </div>
    );
}

const Body = props =>{
    return(
        <Fragment>
            <Notified {...props.notified} />
            <ParagrahpOne {...props.paragraphOne} />
            <ParagrahpTwo />
            <ParagrahpThree {...props.paragraphThree} />
        </Fragment>
    );
}

const Header = props => <Head street={props.street} city={props.city} phone={props.phone} email={props.email} />

class PreviewNotification extends Component{

    normalizeStreet = (address) => {
        return {
            street: `${address.street}`,
            number: new String(address.number).length ? `, ${address.number} ` : null,
            complement: new String(address.complement).length ? `, ${address.complement} ` : null,
            neighborhood: new String(address.neighborhood).length ? `, ${address.neighborhood} ` : null,
        }
    }

    normalizeCity = (address) => {
        return {
            city: `${address.city}`,
            number: new String(address.state).length ? ` - ${address.state} ` : null
        }
    }

    normalizeData = (notification) =>{
        return {
            notified:{
                name: notification.notified.name,
                street: this.normalizeStreet(notification.notified.address),
                city: this.normalizeCity(notification.notified.address),
            },
            paragraphOne:{
                date: notification.date,
                time: notification.time
            },
            paragraphThree:{
                date: notification.createdAt.split(' ')[0]
            }
        }

    }

    render(){
        
        return(
            <Fragment>
                <PreviewDocument 
                    header={ Header }
                    body={ Body } 
                />
            </Fragment>
        )
    }
}

PreviewNotification.propTypes = {
    notification: PropTypes.object.isRequired
};

export default PreviewNotification;