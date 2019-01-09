import React, { Component } from 'react'
import { connect } from "react-redux";
import classnames from "classnames";

import { agencyLayoutBgHandler } from "Actions";

class AgencyLayoutBgProvider extends Component {

	changeLayoutBg(color) {
		this.props.agencyLayoutBgHandler(color)
	}

	render() {
		const { agencyLayoutBgColors } = this.props;
		return (
			<div>
				<ul className="list-unstyled agency-bg mb-0 p-10 text-center">
					<li className="header-title mb-10">
						<span>Background Color </span>
					</li>
					{agencyLayoutBgColors.map((color, key) => (
						<li
							className={classnames("list-inline-item", {
								'active': color.active
							})}
							key={key}
							onClick={() => this.changeLayoutBg(color)}
						>
							<span className={classnames("badge", color.class)}></span>
						</li>
					))}
				</ul>
			</div>
		)
	}
}

const mapStateToProps = ({ settings }) => {
	const { agencyLayoutBgColors } = settings;
	return { agencyLayoutBgColors };
}

export default connect(mapStateToProps, {
	agencyLayoutBgHandler
})(AgencyLayoutBgProvider)