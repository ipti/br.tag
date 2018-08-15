/**
 * Agency Welcome Block
 */

import React, { Component } from 'react'
import axios from 'axios';
import classNames from 'classnames';

//Charts
import AgencyWelcomeBarChart from "Components/Charts/AgencyWelcomeBarChart"

//Chart Data
import {
	WelcomeBarChart1,
	WelcomeBarChart2
} from '../../routes/dashboard/agency/data'

// function to get today weather icon
function getIcon(id) {
	if (id >= 200 && id < 300) {
		return 'wi wi-night-showers'
	} else if (id >= 300 && id < 500) {
		return 'wi day-sleet'
	} else if (id >= 500 && id < 600) {
		return 'wi wi-night-showers'
	} else if (id >= 600 && id < 700) {
		return 'wi wi-day-snow'
	} else if (id >= 700 && id < 800) {
		return 'wi wi-day-fog'
	} else if (id === 800) {
		return 'wi wi-day-sunny'
	} else if (id >= 801 && id < 803) {
		return 'wi wi-night-partly-cloudy'
	} else if (id >= 802 && id < 900) {
		return 'wi wi-day-cloudy'
	} else if (id === 905 || (id >= 951 && id <= 956)) {
		return 'wi wi-day-windy'
	} else if (id >= 900 && id < 1000) {
		return 'wi wi-night-showers'
	}
}

export default class AgencyWelcomeBlock extends Component {
	constructor(props) {
		super(props);
		this.state = {
			city: false,
			countryCode: false,
			todayTemp: false,
			todayTempText: false,
			todayWeatherIcon: '',
		}
	}
	componentDidMount() {
		var appid = 'b1b15e88fa797225412429c1c50c122a1'; // Your api id
		var apikey = '69b72ed255ce5efad910bd946685883a'; //Your apikey
		var city = 'Mohali'; // city name
		axios.get('http://api.openweathermap.org/data/2.5/forecast/daily?q=' + city + '&cnt=6&units=metric&mode=json&appid=' + appid + '&apikey=' + apikey)
			.then(response => {
				this.setState({
					city: response.data.city.name,
					countryCode: response.data.city.country,
					todayTemp: response.data.list[0].temp.max,
					todayTempText: response.data.list[0].weather[0].main,
					todayWeatherIcon: getIcon(response.data.list[0].weather[0].id)
				})
			})
			.catch(error => {
				console.log('Error fetching and parsing data', error);
			});
	}
	render() {
		return (
			<div className="agency-welcome-block p-10 mb-30">
				<div className="row">
					<div className="col-lg-6 col-md-12">
						<div className="d-flex weather-wrap mb-20">
							<span><i className={classNames(this.state.todayWeatherIcon, 'font-3x mr-20')}></i></span>
							<div className="weather-content">
								<span className="mb-5 fs-12 d-block">{this.state.city} ({this.state.countryCode})</span>
								<h2 className="font-weight-light">{this.state.todayTemp}<sup>o</sup> {this.state.todayTempText}</h2>
							</div>
						</div>
						<div className="welcome-message mb-30">
							<h2 className="fw-semi-bold">Good morning, Jacqueline.</h2>
							<p>Here’s what’s happening with your store this week.</p>
						</div>
						<div className="welcome-chart row">
							<div className="col-lg-6 col-sm-6 mb-30 mb-sm-0">
								<span className="fw-semi-bold font-lg d-block mb-5">$21,349.29</span>
								<span className="d-block fs-12 mb-3">Earned Today</span>
								<AgencyWelcomeBarChart
									data={WelcomeBarChart1.data}
									labels={WelcomeBarChart1.labels}
									color={WelcomeBarChart1.color}
								/>
							</div>
							<div className="col-lg-6 col-sm-6">
								<span className="fw-semi-bold font-lg d-block mb-5">15,800</span>
								<span className="d-block fs-12 mb-3">Items Sold</span>
								<AgencyWelcomeBarChart
									data={WelcomeBarChart2.data}
									labels={WelcomeBarChart2.labels}
									color={WelcomeBarChart2.color}
								/>
							</div>
						</div>
					</div>
					<div className="col-lg-6 d-lg-block d-none">
						<div className="d-flex align-items-center justify-content-center">
							<img alt="agency block" src={require('Assets/img/agency-welcome.png')} className="img-fluid" />
						</div>
					</div>
				</div>
			</div>
		)
	}
}
