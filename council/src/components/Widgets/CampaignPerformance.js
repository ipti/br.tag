/**
 * Campaign Performance Widget
 */
import React, { Component } from 'react'
import { Input, FormGroup } from 'reactstrap';

// chart
import CampaignBarChart from 'Components/Charts/CampaignBarChart';

// intl messages
import IntlMessages from 'Util/IntlMessages';

const compaigns = {
	yesterday: {
		labels: ['12:00 AM', '4:00 AM', '8:00 AM', '12:00 PM', '16:00 PM'],
		websiteViews: [600, 900, 660, 750, 800],
		emailSubscription: [400, 550, 400, 400, 450]
	},
	last5Days: {
		labels: ['Mon', 'Tue', 'Wed', 'Thur', 'Fri'],
		websiteViews: [600, 900, 725, 1000, 460],
		emailSubscription: [400, 700, 500, 625, 400]
	},
	last1Month: {
		labels: ['1-5', '6-10', '11-15', '16-20', '21-25'],
		websiteViews: [800, 700, 725, 600, 900],
		emailSubscription: [700, 600, 400, 400, 500]
	},
	last5Months: {
		labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May'],
		websiteViews: [1000, 700, 725, 625, 600],
		emailSubscription: [800, 450, 550, 500, 450]
	}
}

export default class CampaignPerformance extends Component {

	constructor(props) {
		super(props);
		this.state = {
			selectedComapign: compaigns['last5Days'],
			compaigns,
			selected: 'last5Days'
		}
	};

	// get random property of object
	pickRandomProperty(obj) {
		var result;
		var count = 0;
		for (var prop in obj)
			if (Math.random() < 1 / ++count)
				result = prop;
		return result;
	}

	componentDidMount() {
		this.timer = setInterval(() => {
			let randomDataKey = this.pickRandomProperty(this.state.compaigns);
			this.setState({
				selectedComapign: compaigns[randomDataKey],
				selected: randomDataKey
			});
		}, 2000)
	}

	componentWillUnmount() {
		clearInterval(this.timer);
	}

	render() {
		const { labels, websiteViews, emailSubscription } = this.state.selectedComapign;
		return (
			<div>
				<CampaignBarChart
					labels={labels}
					websiteViews={websiteViews}
					emailSubscription={emailSubscription}
				/>
				<div className="d-flex justify-content-between align-items-center mt-15">
					<div className="app-selectbox-sm w-30">
						<FormGroup className="mb-0">
							<Input
								type="select"
								className="fs-12"
								name="select"
								id="exampleSelect"
								onChange={(e) => this.setState({ selectedComapign: this.state.compaigns[e.target.value] })}
								value={this.state.selected}
							>
								<option disabled>Select Campaign</option>
								<option value="last5Days">Last 5 Days</option>
								<option value="yesterday">Yesterday</option>
								<option value="last1Month">Last 1 Month</option>
								<option value="last5Months">Last 5 Months</option>
							</Input>
						</FormGroup>
					</div>
					<span className="fs-12 text-base">
						<i className="mr-5 zmdi zmdi-refresh"></i>
						<IntlMessages id="widgets.updated10Minago" />
					</span>
				</div>
			</div>
		);
	};
};
