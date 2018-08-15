/**
 * Weather V2 Widget
 */
import React, { Component, Fragment } from 'react';
import moment from 'moment';
import axios from 'axios';

// function to get today weather icon
function getIcon(id) {
  switch (id) {
    case id >= 200 && id < 300:
      return 'wi wi-night-showers'
    case id >= 300 && id < 500:
      return 'wi day-sleet';
    case id >= 500 && id < 600:
      return 'wi wi-night-showers';
    case id >= 600 && id < 700:
      return 'wi wi-day-snow';
    case id >= 700 && id < 800:
      return 'wi wi-day-fog';
    case id === 800:
      return 'wi wi-day-sunny';
    case id >= 801 && id < 803:
      return 'wi wi-night-partly-cloudy';
    case id >= 802 && id < 900:
      return 'wi wi-day-cloudy';
    case id === 905 || (id >= 951 && id <= 956):
      return 'wi wi-day-windy';
    case id >= 900 && id < 1000:
      return 'wi wi-night-showers'
    default:
      break;
  }
}

// Main component
export default class WeatherWidgets extends Component {

  state = {
    weatherData: null,
    city: this.props.city ? this.props.city : 'New York'
  }

  componentDidMount() {
    var appid = 'b1b15e88fa797225412429c1c50c122a1'; // Your api id
    var apikey = '69b72ed255ce5efad910bd946685883a'; //Your apikey
    axios.get('http://api.openweathermap.org/data/2.5/forecast/daily?q=' + this.state.city + '&cnt=5&units=metric&mode=json&appid=' + appid + '&apikey=' + apikey)
      .then(response => {
        this.setState({ weatherData: response.data });
      })
      .catch(error => {
        console.log('Error fetching and parsing data', error);
      });
  }

  renderFiveDayForecast() {
    const { weatherData } = this.state;
    let elements = [];
    if (weatherData) {
      for (let i = 1; i < weatherData.list.length; i++) {
        const weather = weatherData.list[i];
        let ele = <li className="d-flex justify-content-between align-items-center" key={weather.dt}>
          <span className="w-50">{this.state.city}, {moment(weather.dt * 1000).format('ddd DD MMMM')}</span>
          <div className="w-icon">
            <i className={getIcon(weather.weather[0].id)}></i>
          </div>
          <span className="d-block">{weather.temp.max}<sup>°C</sup></span>
          <span className="d-block">{weather.weather[0].main}</span>
        </li>;
        elements.push(ele);
      }
    }
    return elements;
  }

  render() {
    const { weatherData } = this.state;
    return (
      <Fragment>
        <div className="weather-top rct-weather-widget overflow-hidden rounded-top">
          {weatherData !== null &&
            <div className="black-overlay weather-over py-20">
              <div className="weather-head mb-20 d-flex justify-content-between">
                <div className="align-items-start">
                  <h4>{weatherData.city.name}</h4>
                  <span>{moment().format('ddd, HH:mm A')}</span>
                </div>
                <i className={getIcon(weatherData.list[0].weather[0].id)}></i>
              </div>
              <div className="weather-temp">
                <h2>{weatherData.list[0].temp.day}<sup>°C</sup></h2>
              </div>
            </div>
          }
        </div>
        <div className="weather-bottom">
          <ul className="list-inline mb-0">
            {this.renderFiveDayForecast()}
          </ul>
        </div>
      </Fragment>
    )
  }
}
