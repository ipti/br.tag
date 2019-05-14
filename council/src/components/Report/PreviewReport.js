import React, { Component, Fragment } from 'react';
import PreviewDocument from 'Components/PreviewDocument/PreviewDocument'
import Header from 'Components/PreviewDocument/Header'
import PropTypes from 'prop-types';
import 'Assets/css/report/style.css';
import renderHTML from 'react-render-html';


class Report extends Component{

    render(){
        const props = this.props;
        return(
            <Fragment>
                <div className="mt-40 report-place" >
                    <h4>{props.place} {props.date}</h4>
                </div>
                <div className="mt-40">
                    <h1 className="text-center">Relatório Informativo</h1>
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
                { props.body && renderHTML(props.body) }
            </div>
        );
    }
}

class ParagrahpTwo extends Component{

    render(){
        return(
            <div>
                <p className="text-center">
                    Atenciosamente
                </p>
                <p className="text-center">
                    Conselheiros Tutelares
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
                <Report {...props.localPlace} />
                <ParagrahpOne {...props.body} />
                <ParagrahpTwo />
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

    normalizeData = (report, institution) =>{
        const { address } = institution
        return {
            localPlace:{
                date: report && report.date ? report.date : '',
                place: address && address.city && address.state ? `${address.city}/${address.state}` : '',
            },
            body: {
                body:  report.description
            }
        }

    }

    normalizeHeaderData = (institution) =>{
        console.log(institution)
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
                <Body {...this.normalizeData(this.props.report, this.props.institution)} />
            </Fragment>
        )
    }
}

PreviewNotification.propTypes = {
    report: PropTypes.object.isRequired,
    institution: PropTypes.object.isRequired,
};

export default PreviewNotification;