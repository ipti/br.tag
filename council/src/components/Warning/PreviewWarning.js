import React, { Component, Fragment } from 'react';
import Header from 'Components/PreviewDocument/Header';
import renderHTML from 'react-render-html';
import PropTypes from 'prop-types';

class ParagrahpOne extends Component {
    render() {
        const { adolescent, representative } = this.props;
        return (
            <Fragment>
                <div className="mt-2">
                    <p className="text-justify my-0">
                        No dia {this.props.date} no Conselho Tutelarde Santa Luzia do Itanhi/ Se,perante os conselheiros,
                        compareceu <strong className="title-header">{adolescent.name} </strong>
                        neste ato represetada por Sr(a). <strong className="title-header">{representative.name}, </strong>
                        {`${representative.nacionality} ${representative.civilStatus}, nascido(a) na cidade de ${representative.city} e portador(a) do RG: ${representative.rg} e CPF: ${representative.cpf}`}
                    </p>
                </div>
            </Fragment>
        );
    }
}

class ParagrahpTwo extends Component {
    render() {
        const reason = this.props.reason;
        return (
            <Fragment>
                <div className="mt-2">
                    <p className="text-justify my-0">
                        {reason}
                    </p>
                </div>
            </Fragment>
        );
    }
}

class ParagrahpThree extends Component {
    render() {
        return (
            <div className="mt-0">
                <p className="text-center my-0">
                    Santa Luzia do Itanhi/Se
                </p>
            </div>
        );
    }
}

class ParagrahpFour extends Component {
    render() {
        return (
            <div className="mt-0">
                <div className="text-left my-0">
                    <div className="row">
                        <div className="col-1">
                            Acordante:
                        </div>
                        <div className="col-11">
                            <input style={{
                                border: 'none',
                                borderBottom: '1px solid #000000'
                            }} />
                        </div>
                    </div>
                    <div className="mt-1">
                        <strong>{this.props.adolescent.name}</strong> (representado(a) por <strong>{this.props.representative.name}</strong>)
                    </div>
                </div>
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
                    <h2 className="text-center">TERMO DE COMPROMISSO E DE ADVERTÊNCIA</h2>
                </div>
                <ParagrahpOne {...props} />
                <ParagrahpTwo {...props} />
                <ParagrahpThree {...props} />
                <ParagrahpFour {...props} />
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

class PreviewWarning extends Component {

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

    normalizeData = (warning) => {
        return {
            adolescent: {
                name: warning.personAdolescent && warning.personAdolescent.name ? warning.personAdolescent.name : ''
            },
            representative: {
                name: warning.personRepresentative && warning.personRepresentative.name ? warning.personRepresentative.name : '',
                nacionality: warning.personRepresentative && warning.personRepresentative.nacionality ? warning.personRepresentative.nacionality : '',
                civilStatus: warning.personRepresentative && warning.personRepresentative.civilStatus ? warning.personRepresentative.civilStatus : '',
                rg: warning.personRepresentative && warning.personRepresentative.rg ? warning.personRepresentative.rg : '',
                cpf: warning.personRepresentative && warning.personRepresentative.cpf ? warning.personRepresentative.cpf : '',
                neighborhood: warning.personRepresentative && warning.personRepresentative.address ? warning.personRepresentative.address.neighborhood : '',
                city: warning.personRepresentative && warning.personRepresentative.address ? warning.personRepresentative.address.city : '',
            },
            reason: warning.reason ? renderHTML(warning.reason) : '',
            date: warning.createdAt ? warning.createdAt.split(' ')[0] : '',
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
                <Body {...this.normalizeData(this.props.warning)} />
            </Fragment>
        )
    }
}

PreviewWarning.propTypes = {
    warning: PropTypes.object.isRequired,
    institution: PropTypes.object.isRequired,
};

export default PreviewWarning;