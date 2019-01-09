/**
 * Language Select Dropdown
 */
import React, { Component } from 'react';
import { connect } from 'react-redux';
import { DropdownToggle, DropdownMenu, Dropdown } from 'reactstrap';
import { Scrollbars } from 'react-custom-scrollbars';
import { Badge } from 'reactstrap';
import $ from 'jquery';
import Tooltip from '@material-ui/core/Tooltip';

// actions
import { setLanguage, rtlLayoutAction } from 'Actions';

class LanguageProvider extends Component {

	state = {
		langDropdownOpen: false
	}

	// function to toggle dropdown menu
	toggle = () => {
		this.setState({
			langDropdownOpen: !this.state.langDropdownOpen
		});
	}

	// on change language
	onChangeLanguage(lang) {
		this.setState({ langDropdownOpen: false });
		this.props.setLanguage(lang);
		if (lang.locale === 'ar' || lang.locale === 'he') {
			this.rtlLayoutHanlder(true);
		} else {
			this.rtlLayoutHanlder(false);
		}
	}

	/**
	 * Rtl Layout Event Hanlder
	 * Use to Enable rtl Layout
	 * @param {*object} event
 */
	rtlLayoutHanlder(isTrue) {
		if (isTrue) {
			$("html").attr("dir", "rtl");
			$('body').addClass('rtl');
		} else {
			$("html").attr("dir", "ltr")
			$('body').removeClass('rtl');
		}
		this.props.rtlLayoutAction(isTrue);
	}

	render() {
		const { locale, languages } = this.props;
		return (
			<Dropdown nav className="list-inline-item language-dropdown tour-step-5" isOpen={this.state.langDropdownOpen} toggle={this.toggle}>
					<DropdownToggle caret nav className="header-icon language-icon">
						<Tooltip title="Languages" placement="bottom">
							<img src={require(`Assets/flag-icons/${locale.icon}.png`)} className="mr-10" width="25" height="16" alt="lang-icon" />
						</Tooltip>
					</DropdownToggle>
					<DropdownMenu>
						<div className="dropdown-content">
							<div className="dropdown-top d-flex justify-content-between rounded-top bg-primary">
									<span className="text-white font-weight-bold">Languages</span>
									<Badge color="warning">3 NEW</Badge>
							</div>
							<Scrollbars className="rct-scroll" autoHeight autoHeightMin={100} autoHeightMax={280}>
								<ul className="list-unstyled mb-0 dropdown-list">
									{languages.map((language, key) => (
										<li key={key} onClick={() => this.onChangeLanguage(language)}>
											<a href="javascript:void(0)">
													<img
														src={require(`Assets/flag-icons/${language.icon}.png`)} className="mr-10"
														width="25"
														height="16"
														alt="lang-icon"
													/>
													{language.name}
											</a>
										</li>
									))}
								</ul>
							</Scrollbars>
						</div>
					</DropdownMenu>
			</Dropdown>
		);
	}
}

// map state to props
const mapStateToProps = ({ settings }) => {
    return settings
};

export default connect(mapStateToProps, {
    setLanguage,
    rtlLayoutAction
})(LanguageProvider);
