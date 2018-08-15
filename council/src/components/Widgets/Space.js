/**
 * Space Widget
 */
import React, { Component } from 'react';
import { Card, CardBody } from 'reactstrap';
import Button from '@material-ui/core/Button';

// chart component
import SpacePieChart from 'Components/Charts/SpacePieChart';

// intl messages
import IntlMessages from 'Util/IntlMessages';

export default class Space extends Component {
	render() {
		const { data } = this.props;
		return (
			<Card className="rct-block">
				<CardBody className="d-flex py-15">
					<div className="mr-15 w-40 d-flex align-items-center">
						<SpacePieChart
							labels={data.chartData.labels}
							datasets={data.chartData.datasets}
							height={97}
							width={100}
						/>
					</div>
					<div>
						<p className="mb-0"><IntlMessages id="components.spaceUsed" /></p>
						<p className="font-3x mb-0">30<sub className="text-dark font-lg">/50GB</sub></p>
						<Button color="primary" className="btn-xs"><IntlMessages id="widgets.buyMore" /></Button>
					</div>
				</CardBody>
			</Card>
		);
	}
}
