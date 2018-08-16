/**
 * Complaint View Item
 */
import React, { Component } from 'react';
import { Scrollbars } from 'react-custom-scrollbars';
import { Badge } from 'reactstrap';
import EmailDetail from 'Routes/mail/components/EmailDetail';

// rct card box
import RctCollapsibleCard from 'Components/RctCollapsibleCard/RctCollapsibleCard';

class ComplaintViewItem extends Component {

	state = {
        activities: null,
        pid: null,
        initialDate: null,
        finalDate: null,
        type: null,
	}

	componentDidMount() {

        var demo = [
            {
                "id": 1,
                "date": "18 de Julho de às 11:35",
                "activity": "Denúncia recebida por <a href=\"javascript:void(0)\">@MariaSantos<\/a>. <br> Criança de 12 anos sofre constantes agressões pelo seu pai, segundo informações as agressões são muito violentas.",
                "status": "primary"
            },
            {
                "id": 2,
                "date": "19 de Julho de às 11:35",
                "activity": "Início da averiguação por <a href=\"javascript:void(0)\">@Jessica<\/a>",
                "status": "warning"
            },
            {
                "id": 3,
                "date": "20 de Julho de às 11:40",
                "activity": "Emissão de parecer técnico por <a href=\"javascript:void(0)\">@Jessica<\/a>",
                "status": "warning"
            },
            {
                "id": 4,
                "date": "20 de Julho de às 12:40",
                "activity": "Denúncia encaminhada para o Ministério Público",
                "status": "secondary"
            },
            {
                "id": 4,
                "date": "21 de Julho de às 12:40",
                "activity": "Início da análise por <a href=\"javascript:void(0)\">@JoaoFontes<\/a>",
                "status": "secondary"
            },
            {
                "id": 4,
                "date": "21 de Julho de às 12:40",
                "activity": "Emissão de parecer por <a href=\"javascript:void(0)\">@JoaoFontes<\/a>",
                "status": "secondary"
            },
            {
                "id": 4,
                "date": "21 de Julho às 12:42",
                "activity": "Denúncia encaminhada para o Conselho Tutelar",
                "status": "warning"
            },
            {
                "id": 7,
                "date": "21 de Julho às 15:42",
                "activity": "Processo concluído",
                "status": "success"
            }
        ];

        this.setState({ 
            activities: demo,
            initialDate: '18/07/2018',
            finalDate: '30/07/2018',
            type: 'Violência doméstica',
            pid: '1235567',
         });
	}

	render() {
		const { activities } = this.state;
		return (
            
            <div className="row">
                 <EmailDetail/>
                <RctCollapsibleCard colClasses="col-md-12">
                <div className="activity-widget">
                    <Scrollbars className="rct-scroll" autoHeight autoHeightMin={120} autoHeightMax={2440} autoHide>
                        <ul className="list-unstyled px-3">
                            {activities && activities.map((activity, key) => (
                                <li key={key}>
                                    <Badge color={activity.status} className="rounded-circle p-0">.</Badge>
                                    <span className="activity-time font-xs text-muted">{activity.date}</span>
                                    <p className="mb-0" dangerouslySetInnerHTML={{ __html: activity.activity }} />
                                </li>
                            ))}
                        </ul>
                    </Scrollbars>
                </div>
            </RctCollapsibleCard >
            <div className="media p-20">
							<img src={require('Assets/avatars/user-15.jpg')} alt="user profile" className="img-fluid rounded-circle mr-15" width="50" height="50" />
							<div className="media-body card p-20">
								<span>Clique aqui para <a href="javascript:void(0)">Responder</a> ou <a href="javascript:void(0)">Encaminhar a denúnica</a></span>
							</div>
						</div>
            </div>
		);
	}
}

export default ComplaintViewItem;
