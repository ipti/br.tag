import React, { Component, Fragment } from 'react';
import Header from 'Components/PreviewDocument/Header';
import renderHTML from 'react-render-html';
import PropTypes from 'prop-types';

class Service extends Component{

    render(){
        const props = this.props;
        return(
            <Fragment>
                <div className="mt-40">
                    <h1 className="text-center">Requisição de Serviços Pública</h1>
                </div>
                <div className="mt-40">
                    <p className="text-justify">O Conselho Tutelar dos Diretos da Criança e do Adolescente, órgão permanente e autônomo, não jurisdicional, encarregado pela sociedade de zelar pelos direitos da criança e do Adolescente definidos na Lei Federal do ECA (art.136, inciso III, letra “a” - nº 8.069/90), vem mui respeitosamente através dos conselheiros que o presente subscreve, requisitar tratamento médico,psicológico ou psiquiátrico, em regime hospitalar ou ambulatorial, conforme previsto na <strong>lei 8069/90 ECA,art 101 parágrafo V.</strong></p>
                    <p>Para o(a) adolescente {props.name} ({props.age} anos), {props.address}.</p>
                </div>
            </Fragment>
        );
    }
}

class ParagrahpOne extends Component{

    render(){
        const props = this.props;
        return(
            <div className="mt-20">
                {renderHTML(props.description)}
            </div>
        );
    }
}

class ParagrahpTwo extends Component{

    render(){
        const props = this.props;
        return(
            <Fragment>
                <div className="mt-20">
                    <p className="text-justify">
                    Isto posto, e considerando o dever elementar do Poder Público em proporcionar, com a mais absoluta prioridade, a efetivação do direito à saúde da criança/adolescente acima nominada, inclusive sob pena de responsabilidade (arts. 4º, caput e par. único c/c 208, caput e inciso VII, da Lei nº 8.069/90), este Conselho Tutelar, usando de sua prerrogativa institucional contida no art. 136, inciso III, alínea “a”, da Lei nº 8.069/90, vem perante Vossa Senhoria requisitar o tratamento do paciente acima referido, sem prejuízo da orientação aos pais e outras providências necessárias a seu tratamento e posterior reabilitação, observando-se em qualquer caso as normas técnicas e jurídicas aplicáveis à matéria.
                    </p>
                    <p className="text-justify">
                        Por fim, informo a Vossa Senhoria que o descumprimento da presente requisição caracteriza, em tese, a infração administrativa tipificada no art. 249, da Lei nº 8.069/90, além de sujeitar os agentes públicos omissos a outras sanções administrativas e civis, nos moldes do previsto nos arts. 5º, 208 e 216, da Lei nº 8.069/90.
                    </p>
                    <p className="text-center mt-40">
                        <strong>Obs: aguardamos respostas no prazo não superior a 10 (dez) dias</strong>
                    </p>
                    <p className="text-center">
                        Desde já quero renovar os votos de consideração e apreço.
                    </p>
                </div>
                <div className="mt-40">
                    <p className="text-center">
                        Atenciosamente,
                    </p>
                    <p className="text-center mt-20">
                        Conselho Tutelar de Santa Luzia do Itanhi / SE {props.date}
                    </p>
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
                <Service {...props.child} />
                <ParagrahpOne {...props.paragraphOne} />
                <ParagrahpTwo {...props.paragraphTwo} />
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

class PreviewService extends Component{

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

    normalizeData = (service) =>{
        return {
            child:{
                name: service.child && service.child.name ? service.child.name : '',
                age: service.child && service.child.age ? service.child.age : '',
                address: service.child && service.child.address ? this.normalizeAddress(service.child.address): '',
            },
            paragraphOne:{
                description: service.description ? service.description : '',
            },
            paragraphTwo:{
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

PreviewService.propTypes = {
    service: PropTypes.object.isRequired,
    institution: PropTypes.object.isRequired,
};

export default PreviewService;