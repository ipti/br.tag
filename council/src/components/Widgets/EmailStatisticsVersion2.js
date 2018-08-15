/**
 * Email Statistics V2
 */
import React, { Component } from 'react';

// chart
import EmailStatisticChart from 'Components/Charts/EmailStatistic';

class EmailStatisticsVersion2 extends Component {
	render() {
		const { data } = this.props;
		return (
			<EmailStatisticChart
				labels={data.labels}
				datasets={data.datasets}
			/>
		);
	}
}

export default EmailStatisticsVersion2;
