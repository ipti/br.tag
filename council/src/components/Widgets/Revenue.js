/**
 * Revenue Widget
 */
import React, { Component } from 'react';

// intl messages
import IntlMessages from 'Util/IntlMessages';

// chart component
import TinyPieChart from 'Components/Charts/TinyPieChart';

export default class Revenue extends Component {
	render() {
		const { data } = this.props;
		return (
			<div className="card">
				<h4 className="card-title"><IntlMessages id="widgets.totalRevenue" /></h4>
				<div className="row">
					<div className="col-sm-6 col-md-6 w-40 mx-auto">
						<TinyPieChart
							labels={data.chartData.labels}
							datasets={data.chartData.datasets}
							height={110}
							width={100}
						/>
					</div>
					<div className="col-sm-6 align-self-center display-n">
						<div className="clearfix mb-15">
							<div className="float-left">
								<span className="badge-primary ladgend">&nbsp;</span>
							</div>
							<div className="float-left">
								<h3 className="mb-0">{data.target}</h3>
								<span className="text-dark fs-14"><IntlMessages id="widgets.target" /></span>
							</div>
						</div>
						<div className="clearfix mb-15">
							<div className="float-left">
								<span className="badge-info ladgend">&nbsp;</span>
							</div>
							<div className="float-left">
								<h3 className="mb-0">{data.lastWeek}</h3>
								<span className="text-dark fs-14"><IntlMessages id="widgets.monthly" /></span>
							</div>
						</div>
					</div>
				</div>
			</div>
		);
	}
}
