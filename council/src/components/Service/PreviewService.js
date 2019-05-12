import React, { Component, Fragment } from 'react';
import PreviewDocument from 'Components/PreviewDocument/PreviewDocument'
import Header from 'Components/PreviewDocument/Header'
import PropTypes from 'prop-types';

class Service extends Component{

    render(){
        const props = this.props;
        return(
            <Fragment>
                <div className="mt-40">
                    <h1 className="text-center">Requisição de Serviço</h1>
                </div>
                <div className="mt-40">
                    <h4 className="title-header">Nome: {props.name}</h4>
                    <h4 className="title-header">Profissão: {props.profession}</h4>
                </div>
            </Fragment>
        );
    }
}

class ParagrahpOne extends Component{

    render(){
        const props = this.props;
        return(
            <div className="d-flex mt-40">
                <p className="text-justify">
                    O conselho Tutelar dos Diretos da Criança e do Adolescente, órgão permanente e autônomo, não jurisdicional, encarregado pela sociedade de zelar pelos direitos da criança e do adolescente definidos na Lei Federal do ECA (art.131-nº8.069/90), Vem mui respeitosamente notificar a senhor(a) para comparecer na sede do Conselho Tutelar nesta, no <strong>Dia {props.date}</strong> ás <strong> {props.time} </strong>
                    Para tratar de assuntos de seu interesse.
                </p>
            </div>
        );
    }
}

class ParagrahpTwo extends Component{

    render(){
        return(
            <div className="d-flex">
                <p className="text-justify">
                    Agradecemos a atenção e lembramos que o não comparecimento injustificado da presente, poderá implicar em medidas judiciais, inclusive condução coercitiva, sem prejuízo de eventual responsabilização por crime ou infração administrativa (art.236 e 249- ECA).
                </p>
            </div>
        );
    }
}

class ParagrahpThree extends Component{

    render(){
        const props = this.props;
        return(
            <div className="mt-40">
                <p className="text-left">
                    Atenciosamente,
                </p>
                <p className="text-left mt-20">
                    Conselho Tutelar de Santa Luzia do Itanhi/Se {props.date}
                </p>
            </div>
        );
    }
}

class Body extends Component{

    render(){
        const props = this.props;
        return(
            <div className="body" style={{margin: '2px 20px'}}>
                <Service {...props.notified} />
                <ParagrahpOne {...props.paragraphOne} />
                <ParagrahpTwo />
                <ParagrahpThree {...props.paragraphThree} />
            </div>
        );
    }
}

class Head extends Component{

    render(){
        const props = this.props;
        return(
            <Header street={props.street} city={props.city} phone={props.phone} email={props.email} />
        );
    }
} 

class PreviewNotification extends Component{

    normalizeStreet = (address) => {
        const data = {
            street: `${address.street}`,
            number: new String(address.number).length ? `, ${address.number} ` : null,
            complement: new String(address.complement).length ? `, ${address.complement} ` : null,
            neighborhood: new String(address.neighborhood).length ? `, ${address.neighborhood} ` : null,
        }

        return Object.values(data).join(' ');
    }

    normalizeCity = (address) => {
        const data = {
            city: `${address.city}`,
            state: new String(address.state).length ? ` - ${address.state} ` : null
        }

        return Object.values(data).join(' ');
    }

    normalizeData = (service) =>{
        console.log(service)
        return {
            notified:{
                name: service.notified && service.notified.name ? service.notified.name : '',
                street: service.notified && service.notified.address ? this.normalizeStreet(service.notified.address): '',
                city: service.notified && service.notified.address ? this.normalizeCity(service.notified.address) : '',
                profession: service.notified && service.notified.profession ? service.notified.profession : '',
            },
            paragraphOne:{
                date: service.date ? service.date : '',
                time: service.time ? service.time : ''
            },
            paragraphThree:{
                date: service.createdAt ? service.createdAt.split(' ')[0] : ''
            }
        }

    }

    normalizeHeaderData = (institution) =>{
        const street = institution.address && institution.address.street ? `${institution.address.street}`: '';
        const number = institution.address && institution.address.number && new String(institution.address.number).length ? `, ${institution.address.number} ` : null;

        return {
            street:`${street}${number}`,
            city: institution.address && institution.address.city ? institution.address.city : '',
            phone: institution.phone ? institution.phone : '',
            email: institution.email ? institution.email : '',
        }

    }

    render(){
        
        return(
            <Fragment>
                <Head {...this.normalizeHeaderData(this.props.institution)} />
                <Body {...this.normalizeData(this.props.service)} />
            </Fragment>
        )
    }
}

PreviewNotification.propTypes = {
    service: PropTypes.object.isRequired,
    institution: PropTypes.object.isRequired,
};

export default PreviewNotification;