/**
 * Stock Exchange Widget
 */
import React, { Component } from 'react';
import axios from 'axios';
import List from '@material-ui/core/List';
import ListItem from '@material-ui/core/ListItem';

// section loader
import RctSectionLoader from 'Components/RctSectionLoader/RctSectionLoader';

// rct card footer
import { RctCardFooter } from 'Components/RctCard';

import IntlMessages from 'Util/IntlMessages';

class StockExchange extends Component {

	state = {
		ratesData: null,
		loading: false
	}

	componentDidMount() {
		this.setState({ loading: true });
		this.getCurrencyRates();
	}

	// get currency rates
	getCurrencyRates() {
		axios.get('http://data.fixer.io/api/latest?access_key=4e83fa57182d17c76a14535831d547c6')
			.then((response) => {
				this.setState({ loading: false, ratesData: response.data })
			})
			.catch(error => {
				console.log(error);
				this.setState({ loading: false })
			})
	}

	render() {
		const { ratesData, loading } = this.state;
		if (loading) {
			return <RctSectionLoader />
		}
		return (
			<div className="stock-exchange">
				{ratesData !== null &&
					<List className="list-unstyled p-0">
						<ListItem>
							<span><img src={require('Assets/flag-icons/icons8-canada.png')} className="img-fluid mr-10" alt="cad"/> CAD (Canadian Dollar)</span>
							<span><i className="ti-arrow-up text-success"></i> {ratesData.rates.CAD.toFixed(2)}</span>
						</ListItem>
						<ListItem>
							<span><img src={require('Assets/flag-icons/icons8-germany.png')} className="img-fluid mr-10" alt="eur" /> EUR (Euro)</span>
							<span><i className="ti-arrow-down text-danger"></i> {ratesData.rates.EUR.toFixed(2)}</span>
						</ListItem>
						<ListItem>
							<span><img src={require('Assets/flag-icons/icons8-south_korea.png')} className="img-fluid mr-10" alt="krw"/> KRW (Korea)</span>
							<span><i className="ti-arrow-down text-danger"></i> {ratesData.rates.NZD.toFixed(2)}</span>
						</ListItem>
						<ListItem>
							<span><img src={require('Assets/flag-icons/icons8-india.png')} className="img-fluid mr-10" alt="inr" /> INR (Indian Rupees)</span>
							<span><i className="ti-arrow-up text-success"></i> {ratesData.rates.INR.toFixed(2)}</span>
						</ListItem>
						<ListItem>
							<span><img src={require('Assets/flag-icons/icons8-singapore.png')} className="img-fluid mr-10" alt="sgd" /> SGD (Singapore Dollar)</span>
							<span><i className="ti-arrow-down text-danger"></i> {ratesData.rates.SGD.toFixed(2)}</span>
						</ListItem>
					</List>
				}
				<RctCardFooter customClasses="border-0 fs-12 text-base">
					<i className="mr-5 zmdi zmdi-refresh"></i>
					<IntlMessages id="widgets.updated10Minago" />
				</RctCardFooter>
			</div>
		);
	}
}

export default StockExchange;
