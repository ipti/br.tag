/**
 * Search Form
 */
import React, { Component } from 'react'
import { Input } from 'reactstrap';

export default class SearchForm extends Component {
	render() {
		return (
			<div className="search-wrapper">
				<Input type="search" className="search-input-lg" placeholder="Search.." />
			</div>
		)
	}
}
