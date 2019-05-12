import React, { Component, Fragment } from 'react';
import Header from 'Components/PreviewDocument/Header'
import PropTypes from 'prop-types';

class Applicant extends Component {
    render() {
        const applicant = this.props.applicant;
        return (
            <Fragment>
                <div className="mt-2">
                    <p className="text-justify my-0">
                        <strong className="title-header">Requerente: {applicant.name} </strong>
                        menor impúbere neste ato representado por sua Genitora, <strong>{applicant.motherName} </strong>
                        portadora do RG: <strong>{applicant.rg} </strong> e CPF: <strong>{applicant.cpf}, </strong>
                        residente e domiciliada no(a) {`${applicant.neighborhood} ${applicant.street} ${applicant.number} `}
                    </p>
                    <p className="text-justify my-0">
                        <strong>Cidade: </strong>{`${applicant.city}/${applicant.state}`}
                    </p>
                </div>
            </Fragment>
        );
    }
}


class Representative extends Component {
    render() {
        const representative = this.props.representative;
        return (
            <Fragment>
                <div className="mt-2">
                    <p className="text-justify my-0">
                        <strong className="title-header">Requerido: </strong>{representative.name}
                    </p>
                    <p className="text-justify my-0">
                        <strong className="title-header">Endereço: </strong>{`${representative.neighborhood} ${representative.street} ${representative.number} `}
                    </p>
                    <p className="text-justify my-0">
                        <strong className="title-header">Cidade: </strong>{`${representative.city}/${representative.state}`}
                    </p>
                </div>
            </Fragment>
        );
    }
}

class Reason extends Component {
    render() {
        const reason = this.props.reason;
        return (
            <Fragment>
                <div className="mt-2">
                    <p className="text-justify my-0">
                        <strong className="title-header">Obs: </strong>{reason}
                    </p>
                </div>
            </Fragment>
        );
    }
}

class ParagrahpOne extends Component {
    render() {
        return (
            <div className="mt-20">
                <p className="text-center my-0">
                    Desde já quero renovar os votos de consideração e apreço.
                </p>
                <p className="text-center my-0">
                    Atenciosamente,
                </p>
                <p className="text-center my-0">
                    Conselho Tutelar
                </p>
            </div>
        );
    }
}

class ParagrahpTwo extends Component {
    render() {
        return (
            <div className="mt-0">
                <p className="text-left my-0">
                    <strong>Procuradoria Municipal</strong>
                </p>
            </div>
        );
    }
}

class ParagrahpThree extends Component {
    render() {
        const date = this.props.date;
        return (
            <div className="mt-0">
                <p className="text-right my-0">
                    Santa Luzia do Itanhi/Se {date}
                </p>
            </div>
        );
    }
}

class Body extends Component {

    render() {
        const props = this.props;
        return (
            <div className="body" style={{ margin: '2px 20px' }}>
                <div className="mt-40">
                    <h2 className="text-center">Ação de Alimentos</h2>
                </div>
                <Applicant {...props} />
                <Representative {...props} />
                <Reason {...props} />
                <ParagrahpOne {...props} />
                <ParagrahpTwo {...props} />
                <ParagrahpThree {...props} />
            </div>
        );
    }
}

class Head extends Component {

    render() {
        const props = this.props;
        return (
            <Header street={props.street} city={props.city} phone={props.phone} email={props.email} />
        );
    }
}

class PreviewFood extends Component {

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

    normalizeData = (food) => {
        return {
            applicant: {
                name: food.personApplicant && food.personApplicant.name ? food.personApplicant.name : '',
                motherName: food.personApplicant && food.personApplicant.mother ? food.personApplicant.mother : '',
                rg: food.personApplicant && food.personApplicant.rg ? food.personApplicant.rg : '',
                cpf: food.personApplicant && food.personApplicant.cpf ? food.personApplicant.cpf : '',
                neighborhood: food.personApplicant && food.personApplicant.address ? food.personApplicant.address.neighborhood : '',
                street: food.personApplicant && food.personApplicant.address ? food.personApplicant.address.street : '',
                number: food.personApplicant && food.personApplicant.address ? food.personApplicant.address.number : '',
                city: food.personApplicant && food.personApplicant.address ? food.personApplicant.address.city : '',
                state: food.personApplicant && food.personApplicant.address ? food.personApplicant.address.state : '',
            },
            representative: {
                name: food.personRepresentative && food.personRepresentative.name ? food.personRepresentative.name : '',
                neighborhood: food.personRepresentative && food.personRepresentative.address ? food.personRepresentative.address.neighborhood : '',
                street: food.personRepresentative && food.personRepresentative.address ? food.personRepresentative.address.street : '',
                number: food.personRepresentative && food.personRepresentative.address ? food.personRepresentative.address.number : '',
                city: food.personRepresentative && food.personRepresentative.address ? food.personRepresentative.address.city : '',
                state: food.personRepresentative && food.personRepresentative.address ? food.personRepresentative.address.state : '',
            },
            reason: food.reason ? food.reason : '',
            date: food.createdAt ? food.createdAt.split(' ')[0] : '',
        }

    }

    normalizeHeaderData = (institution) => {
        const street = institution.address && institution.address.street ? `${institution.address.street}` : '';
        const number = institution.address && institution.address.number && new String(institution.address.number).length ? `, ${institution.address.number} ` : null;

        return {
            street: `${street}${number}`,
            city: institution.address && institution.address.city ? institution.address.city : '',
            phone: institution.phone ? institution.phone : '',
            email: institution.email ? institution.email : '',
        }

    }

    render() {
        return (
            <Fragment>
                <Head {...this.normalizeHeaderData(this.props.institution)} />
                <Body {...this.normalizeData(this.props.food)} />
            </Fragment>
        )
    }
}

PreviewFood.propTypes = {
    food: PropTypes.object.isRequired,
    institution: PropTypes.object.isRequired,
};

export default PreviewFood;