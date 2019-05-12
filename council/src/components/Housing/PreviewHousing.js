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
                    <h1 className="text-center">Termo de Abrigamento</h1>
                </div>
                <div className="mt-40">
                    <h4 className="title-header">De: {props.sender}</h4>
                    <h4 className="title-header">Para: {props.receiver}</h4>
                </div>
                <div className="d-flex mt-20">
                    <p className="text-justify">
                        O conselho Tutelar dos Direito da criança e do Adolescente, Órgão permanente e autônomo, não jurisdicional encarregado pela sociedade de zelar pelo cumprimento dos direitos da criança e do adolescente (Lei Federal Nº8.069/90). No uso de suas atribuições contidas no Art.136,ll (Lei Federal Nº 8.069/90), requisitar Abrigo como medida prevista no Art.101, Vll (Lei Federal Nº 8.069/90)
                    </p>
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
                <div className="mt-40">
                    <h4 className="title-header">Adolescente: {props.name}</h4>
                    <h4 className="title-header">Nascimento: {props.birthday}</h4>
                    <h4 className="title-header">Filiação: {props.motherAndFather}</h4>
                    <h4 className="title-header">Endereço: {props.address}</h4>
                </div>
                <div className="d-flex mt-20">
                    <h4 className="title-header">Motivo:</h4>
                    <div className="text-justify" style={{padding: '5px 0 0 5px'}}>
                        {renderHTML(props.motive)}
                    </div>
                </div>
            </Fragment>
        );
    }
}

class ParagrahpThree extends Component{

    render(){
        return(
            <div className="mt-40">
                <h4 className="text-center">
                    Lei Federal Nº 8.069/90
                </h4>
                <p className="text-left mt-20">
                    <strong>Art.92 </strong> O dirigente da entidade de abrigo é equipado para todos os efeitos de direitos.
                </p>
                <p className="text-left mt-20">
                    <strong>Art.98 </strong> As medidas de proteção à criança e ao adolescente são aplicáveis sempre que os direitos reconhecidos nesta Lei forem ameaçados ou violados:
                </p>
                <div className="ml-5">
                    <p className="text-left mt-20">
                        <strong>III – em razão de sua conduta. </strong> 
                    </p>
                    <p className="text-left mt-20">
                        <strong>Art.136, </strong> lll- Promover a execução de suas decisões podendo para:
                    </p>
                    <p className="text-left mt-20 ml-5">
                        a) Requisitar serviço públicos nas área de saúde, educação, serviço social, previdência, trabalho e segurança.
                    </p>
                    <p className="text-left mt-20">
                        <strong>Art.236. </strong> Impedir ou embaraçar a ação de autoridade jurisdicional, membro do Conselho Tutelar ou representante do Ministério Público no exercício de sua função: Pena prevista em lei: Detenção de seis a dois anos
                    </p>
                </div>
                <p className="text-center mt-40">
                    Consolheiros Tutelares
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
                <ParagrahpOne {...props.paragraphOne} />
                <ParagrahpTwo {...props.paragraphTwo} />
                <ParagrahpThree />
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

    normalizeAddress = (address) => {
        const data = {
            street: `${address.street}`,
            number: new String(address.number).length ? `, ${address.number} ` : null,
            complement: new String(address.complement).length ? `, ${address.complement} ` : null,
            neighborhood: new String(address.neighborhood).length ? `, ${address.neighborhood} ` : null,
            city: `${address.city}`,
            state: new String(address.state).length ? ` - ${address.state} ` : null
        }

        return Object.values(data).join(' ');
    }

    normalizeFiliation = (child) => {
        const data = {
            mother: child.mother ? child.mother : '',
            father: child.father ? child.father : '',
        }

        return Object.values(data).join(' e ');
    }

    normalizeData = (housing) =>{
        return {
            paragraphOne:{
                sender: housing.sender && housing.sender.name ? housing.sender.name : '',
                receiver: housing.receiver && housing.receiver.name ? housing.receiver.name : '',
                street: housing.notified && housing.notified.address ? this.normalizeStreet(housing.notified.address): '',
                city: housing.notified && housing.notified.address ? this.normalizeCity(housing.notified.address) : '',
            },
            paragraphTwo:{
                name: housing.child && housing.child.name ? housing.child.name : '',
                birthday: housing.child && housing.child.birthday ? housing.child.birthday : '',
                address: housing.child && housing.child.address ? this.normalizeAddress(housing.child.address): '',
                motherAndFather: housing.child ? this.normalizeFiliation(housing.child): '',
                motive: housing.motive ? housing.motive : '<p></p>'
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
                <Body {...this.normalizeData(this.props.housing)} />
            </Fragment>
        )
    }
}

PreviewNotification.propTypes = {
    housing: PropTypes.object.isRequired,
    institution: PropTypes.object.isRequired,
};

export default PreviewNotification;