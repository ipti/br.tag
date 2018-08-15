/**
 * Traffic Sales Widget
 */
import React from 'react';
import Button from '@material-ui/core/Button';

// card component
import { RctCardFooter } from 'Components/RctCard';

// chart
import HorizontalBarChart from 'Components/Charts/HorizontalBarChart';

// intl messages
import IntlMessages from 'Util/IntlMessages';

const TrafficChannel = ({ label, chartdata, labels }) => (
	<div className="sales-chart-wrap">
		<div className="p-15">
			<HorizontalBarChart
				label={label}
				chartdata={chartdata}
				labels={labels}
				height={168}
			/>
		</div>
		<RctCardFooter customClasses="d-flex justify-content-between align-items-center">
			<Button size="small" variant="raised" color="primary" className="text-white"> <IntlMessages id="button.goToCampaign" /></Button>
			<p className="fs-12 mb-0 text-base">
				<span><i className="mr-5 zmdi zmdi-refresh"></i></span>
				<IntlMessages id="widgets.updated10Minago" />
			</p>
		</RctCardFooter >
	</div >
);

export default TrafficChannel;
