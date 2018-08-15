/**
 * Activity Board
 */
import React, { Component } from 'react'
import IconButton from '@material-ui/core/IconButton';
import { Progress } from 'reactstrap';
import Checkbox from '@material-ui/core/Checkbox';
import { Scrollbars } from 'react-custom-scrollbars';
import TextField from '@material-ui/core/TextField';
import classnames from 'classnames';
import update from 'react-addons-update';
import List from '@material-ui/core/List';
import ListItem from '@material-ui/core/ListItem';

// api
import api from 'Api';

// card component
import { RctCardFooter } from 'Components/RctCard';

// intl messages
import IntlMessages from 'Util/IntlMessages';

// rct section loader
import RctSectionLoader from 'Components/RctSectionLoader/RctSectionLoader';

export default class ActivityBoard extends Component {
	state = {
		activityData: null,
		sectionReload: false,
		assetsData: null,
	}

	componentDidMount() {
		this.getAssetstData();
		this.getChecklistData();
	}

	// assets Data

	getAssetstData() {
		this.setState({ sectionReload: true });
		api.get('galleryImages.js')
			.then((response) => {
				this.setState({ assetsData: response.data, sectionReload: false });
			}).catch(error => {
				this.setState({ assetsData: null, sectionReload: false });
			})
	}

	// Checklist Data

	getChecklistData() {
		this.setState({ sectionReload: true });
		api.get('ActivityBoardData.js')
			.then((response) => {
				this.setState({ activityData: response.data, sectionReload: false });
			}).catch(error => {
				this.setState({ activityData: null, sectionReload: false });
			})
	}

	// on handle change task
	handleChange(value, task) {
		let selectedTaskIndex = this.state.activityData.indexOf(task);
		let newState = update(this.state, {
			activityData: {
				[selectedTaskIndex]: {
					completed: { $set: value }
				}
			}
		});
		this.setState({ sectionReload: true });
		let self = this;
		setTimeout(() => {
			self.setState({ activityData: newState.activityData, sectionReload: false });
		}, 1500);
	}

	render() {
		const { activityData, assetsData } = this.state;
		return (
			<div className="activity-board-wrapper">
				{this.state.sectionReload &&
					<RctSectionLoader />
				}
				<Scrollbars className="rct-scroll" autoHeight autoHeightMin={100} autoHeightMax={600} autoHide>
					<ul className="mb-0 list-unstyled">
						<li className="border-bottom">
							<div className="activity-heading d-flex p-4 border-bottom">
								<h3 className="mb-0">Reactify Redesign</h3>
							</div>
							<div className="activity-description p-4">
								<h4 className="mb-4"><IntlMessages id="widgets.description" /></h4>
								<div className="comment-box mb-4">
									<p className="small-text">
										Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
                              tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
                              quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
                           </p>
								</div>
								<h4 className="mb-4">Reactify Redesign Assets</h4>
								<ul className="mb-0 list-inline attachment-wrap">
									{assetsData && assetsData.map((data, key) => (
										<li key={key} className="list-inline-item overlay-wrap overflow-hidden rounded">
											<img src={data.imageUrl} className="size-120 rounded img-fluid" alt="img"/>
											<div className="overlay-content">
												<a href="javascript:void(0)" className="d-flex align-items-center justify-content-center h-100 font-2x text-white">
													<i className="zmdi zmdi-download"></i>
												</a>
											</div>
										</li>
									))}
								</ul>
							</div>
						</li>
						<li>
							<div className="activity-heading d-flex p-4 border-bottom">
								<h3 className="mb-0"><IntlMessages id="widgets.checklist" /></h3>
							</div>
							<div className="p-4">
								<Progress color="primary" className="mb-3" value="80">80% Completed</Progress>
								<List className="p-0">
									{activityData && activityData.map((task, key) => (
										<ListItem key={key} onClick={(e) => this.handleChange(!task.completed, task)} button className="p-0">
											<div className={classnames('d-flex  align-items-center', { 'strike': task.completed })}>
												<Checkbox
													color="primary"
													checked={task.completed}
													className="mr-20 "
													onChange={(e) => this.handleChange(e.target.checked, task)}
												/>
												<p className="mb-0">{task.title}</p>
											</div>
										</ListItem>
									))}
								</List>
							</div>
						</li>
					</ul>
				</Scrollbars>
				<RctCardFooter className="bg-light">
					<span className="fs-12 text-base">
						<i className="mr-15 zmdi zmdi-refresh"></i>
						<IntlMessages id="widgets.updated10Minago" />
					</span>
				</RctCardFooter>
			</div>
		)
	}
}
