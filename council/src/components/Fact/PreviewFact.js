import React, { Component, Fragment } from 'react';
import Header from 'Components/PreviewDocument/Header';
import renderHTML from 'react-render-html';
import PropTypes from 'prop-types';

class ParagrahpOne extends Component{

    render(){
        const props = this.props;
        return(
            <Fragment>
                <div className="mt-40">
                    <h1 className="text-center">Registro de Fato</h1>
                </div>
                <div className="mt-40">
                    <p className="title-header">Criança/Adolescente: {props.name}</p>
                    <p className="title-header">
                        <span className="mr-5">Data: {props.date}</span>
                        <span>Hora: {props.time}</span>
                    </p>
                    <p className="title-header">Sexo: {props.sex}</p>
                    <p className="title-header">Endereço: {props.address}</p>
                </div>
            </Fragment>
        );
    }
}

class ParagrahpTwo extends Component{

    render(){
        const props = this.props;
        return(
            <Fragment>
                <div className="d-flex mt-40">
                    <h4 className="title-header">Descrição:</h4>
                    <div className="text-justify" style={{padding: '5px 0 0 5px'}}>
                        {renderHTML(props.description)}
                    </div>
                </div>
            </Fragment>
        );
    }
}

class ParagrahpThree extends Component{

    render(){
        const props = this.props;
        return(
            <Fragment>
                <div className="d-flex mt-40">
                    <h4 className="title-header">Providências:</h4>
                    <div className="text-justify" style={{padding: '5px 0 0 5px'}}>
                        {renderHTML(props.providence)}
                    </div>
                </div>
                <div className="mt-40">
                    <p className="text-center">___________________________________________________________________</p>
                    <p className="text-center">Assinatura do Declarante</p>
                </div>
            </Fragment>
        );
    }
}


class Body extends Component{

    render(){
        const props = this.props;
        return(
            <div className="body" style={{margin: '2px 20px'}}>
                <ParagrahpOne {...props.paragraphOne} />
                <ParagrahpTwo {...props.paragraphTwo} />
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

class PreviewFact extends Component{

    normalizeAddress = (address) => {
        const data = {
            street: `${address.street}`,
            number: new String(address.number).length ? `, ${address.number} ` : '',
            complement: new String(address.complement).length ? `, ${address.complement} ` : '',
            neighborhood: new String(address.neighborhood).length ? `, ${address.neighborhood} ` : '',
            city: new String(address.city).length ? `, ${address.city} ` : '',
            state: new String(address.state).length ? ` - ${address.state} ` : ''
        }

        return Object.values(data).join(' ');
    }

    normalizeData = (fact) =>{
        return {
            paragraphOne:{
                name: fact.child && fact.child.name ? fact.child.name : '',
                sex: fact.child && fact.child.sex ? fact.child.sex === 'M' ? 'Masculino' : 'Feminino' : '',
                date: fact.createdAt ? fact.createdAt.split(' ')[0] : '',
                time: fact.createdAt ? fact.createdAt.split(' ')[1] : '',
                address: fact.child && fact.child.address ? this.normalizeAddress(fact.child.address): '',
            },
            paragraphTwo:{
                description: fact.description ? fact.description : '<p></p>'
            },
            paragraphThree:{
                providence: fact.providence ? fact.providence : '<p></p>'
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
                <Body {...this.normalizeData(this.props.fact)} />
            </Fragment>
        )
    }
}

PreviewFact.propTypes = {
    fact: PropTypes.object.isRequired,
    institution: PropTypes.object.isRequired,
};

export default PreviewFact;