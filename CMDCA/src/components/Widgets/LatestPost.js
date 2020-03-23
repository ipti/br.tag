/**
 * Latest Post Widget
 */
import React, { Component } from 'react';
import List from '@material-ui/core/List';
import ListItem from '@material-ui/core/ListItem';
import update from 'react-addons-update';
import { Scrollbars } from 'react-custom-scrollbars';
import {
	Modal,
	ModalHeader,
	ModalBody,
	ModalFooter,
	Form,
	FormGroup,
	Label,
	Input
} from 'reactstrap';
import Button from '@material-ui/core/Button';
import Snackbar from '@material-ui/core/Snackbar';

// api
import api from 'Api';

//Helper
import { getTheDate, convertDateToTimeStamp } from 'Helpers/helpers';

// card component
import { RctCardFooter } from 'Components/RctCard';

// rct section loader
import RctSectionLoader from 'Components/RctSectionLoader/RctSectionLoader';

// intl messages
import IntlMessages from 'Util/IntlMessages';

export default class LatestPost extends Component {

	state = {
		blogPostData: null,
		sectionReload: false,
		snackbar: false,
		snackbarMessage: '',
		editPostModal: false,
		editPost: null,
		addNewPostForm: false,
		addNewPostDetails: {
			body: '',
			title: '',
			id: null,
			date: null,
			thumbnail: ''
		}
	}

	componentDidMount() {
		this.getBlogData();
	}

	// get Blog Data
	getBlogData() {
		this.setState({ sectionReload: true });
		api.get('blogData.js')
			.then((response) => {
				this.setState({ blogPostData: response.data, sectionReload: false });
			}).catch(error => {
				this.setState({ blogPostData: null, sectionReload: false });
			})
	}

	/**
     * On Delete Post
   */
	onDeletePost(e, post) {
		e.stopPropagation();
		this.setState({ sectionReload: true })
		let posts = this.state.blogPostData;
		let index = posts.indexOf(post);
		let self = this;
		setTimeout(() => {
			posts.splice(index, 1);
			self.setState({ blogPostData: posts, sectionReload: false, snackbar: true, snackbarMessage: 'Post Has Been Moved To Trash' });
		}, 1500);
	}
	// edit Post
	onEditPost(data) {
		this.setState({ editPostModal: true, editPost: data, addNewPostForm: false });
	}

	// toggle edit Post modal
	toggleEditPostModal = () => {
		this.setState({
			editPostModal: !this.state.editPostModal
		});
	}

	// submit Post edit form
	onSubmitPostEditDetailForm() {
		const { editPost, blogPostData } = this.state;
		if (editPost.title !== '' && editPost.body !== '') {
			this.setState({
				editPostModal: false,
				sectionReload: true
			});
			let indexOfPost;
			for (let i = 0; i < blogPostData.length; i++) {
				const post = blogPostData[i];
				if (post.id === editPost.id) {
					indexOfPost = i;
				}
			}
			let self = this;
			setTimeout(() => {
				self.setState({ sectionReload: false, snackbar: true, snackbarMessage: 'Post Updated Successfully' });
				self.setState({
					blogPostData: update(blogPostData,
						{
							[indexOfPost]: { $set: editPost }
						}
					)
				});
			}, 1500);
		}
	}

	// on change Post details
	onChangePostDetails(key, value) {
		this.setState({
			editPost: {
				...this.state.editPost,
				[key]: value
			}
		});
	}

	// add new Post
	addNewPost() {
		this.setState({
			editPostModal: true,
			addNewPostForm: true,
			editPost: null,
			addNewPostDetails: {
				body: '',
				title: '',
				id: null,
				comments: 0,
				views: 0,
				likes: 0,
				thumbnail: "http://via.placeholder.com/63X63"
			}
		});
	}

	// on change Post add new form value
	onChangePostAddNewForm(key, value) {
		this.setState({
			addNewPostDetails: {
				...this.state.addNewPostDetails,
				[key]: value
			}
		})
	}

	// on submit add new Post form
	onSubmitAddNewPostForm() {
		const { addNewPostDetails } = this.state;
		if (addNewPostDetails.title !== '' && addNewPostDetails.body !== '') {
			this.setState({ editPostModal: false, sectionReload: true });
			let newPost = addNewPostDetails;
			newPost.date = new Date().getTime() / 1000;
			let newPosts = this.state.blogPostData;
			let self = this;
			setTimeout(() => {
				newPosts.push(newPost);
				self.setState({ blogPostData: newPosts, sectionReload: false, snackbar: true, snackbarMessage: 'Post Added Successfully' });
			}, 1500);
		}
	}
	render() {
		const { editPostModal, addNewPostForm, editPost, snackbar, successMessage, addNewPostDetails } = this.state;
		return (
			<div className="blog-list-wrap">
				{this.state.sectionReload &&
					<RctSectionLoader />
				}
				<Scrollbars className="rct-scroll" autoHeight autoHeightMin={100} autoHeightMax={600} autoHide>
					<List className="p-0">
						{this.state.blogPostData && this.state.blogPostData.map((data, key) => (
							<ListItem key={key} button className="post-item align-items-center justify-content-between py-25">
								<div className="post-content d-flex">
									<div className="post-img mr-20">
										<img src={data.thumbnail} alt="post-img" className="img-fluid" />
									</div>
									<div className="post-info">
										<h5><a href="#">{data.title}</a></h5>
										<div className="meta-info fs-12 text-muted mb-5">
											<span className="mr-15 d-inline-block"><i className="zmdi zmdi-time mr-5"></i>{getTheDate(data.date, 'DD MMM YYYY')}</span>
											<span className="mr-15 d-inline-block"><i className="zmdi zmdi-comment mr-5"></i>{data.comments} comments</span>
											<span className="mr-15 d-inline-block"><i className="zmdi zmdi-favorite mr-5"></i>{data.likes} Likes</span>
											<span className="mr-15 d-inline-block"><i className="zmdi zmdi-eye mr-5"></i>{data.views} Views</span>
										</div>
										<p className="mb-0">{data.body}</p>
									</div>
								</div>
								<div className="d-flex hover-action">
									<Button className="btn-success text-white m-5" variant="fab" mini onClick={() => this.onEditPost(data)}>
										<i className="zmdi zmdi-edit"></i>
									</Button>
									<Button className="btn-danger text-white m-5" variant="fab" mini onClick={(e) => this.onDeletePost(e, data)}>
										<i className="zmdi zmdi-delete"></i>
									</Button>
								</div>
							</ListItem>
						))}
					</List>
				</Scrollbars>
				<RctCardFooter customClasses="d-flex justify-content-between align-items-center">
					<Button variant="raised" color="primary" className="text-white" onClick={() => this.addNewPost()}>
						<IntlMessages id="widgets.addNew" />
					</Button>
					<span className="fs-12 text-base">
						<i className="mr-15 zmdi zmdi-refresh"></i>
						<IntlMessages id="widgets.updated10Minago" />
					</span>
				</RctCardFooter>
				{editPostModal &&
					<Modal
						isOpen={editPostModal}
						toggle={this.toggleEditPostModal}
					>
						<ModalHeader toggle={this.toggleEditPostModal}>
							{addNewPostForm ? 'Add New Post' : 'Edit Post'}
						</ModalHeader>
						<ModalBody>
							{addNewPostForm ?
								<Form>
									<FormGroup>
										<Label for="postTitle">Title</Label>
										<Input
											type="text"
											name="name"
											id="postTitle"
											value={addNewPostDetails.title}
											onChange={(e) => this.onChangePostAddNewForm('title', e.target.value)}
										/>
									</FormGroup>
									<FormGroup>
										<Label for="postBody">Content</Label>
										<Input
											type="textarea"
											name="textarea"
											id="postBody"
											value={addNewPostDetails.body}
											onChange={(e) => this.onChangePostAddNewForm('body', e.target.value)}
										/>
									</FormGroup>
								</Form>
								: <Form>
									<FormGroup>
										<Label for="postTitle">Tilte</Label>
										<Input
											type="text"
											name="title"
											id="postTitle"
											value={editPost.title}
											onChange={(e) => this.onChangePostDetails('title', e.target.value)}
										/>
									</FormGroup>
									<FormGroup>
										<Label for="postContent">Content</Label>
										<Input
											type="textarea"
											name="content"
											id="postContent"
											value={editPost.body}
											onChange={(e) => this.onChangePostDetails('body', e.target.value)}
										/>
									</FormGroup>
								</Form>
							}
						</ModalBody>
						<ModalFooter>
							{addNewPostForm ?
								<div>
									<Button variant="raised" color="primary" className="text-white" onClick={() => this.onSubmitAddNewPostForm()}><IntlMessages id="button.add" /></Button>{' '}
									<Button variant="raised" className="btn-danger text-white" onClick={this.toggleEditPostModal}><IntlMessages id="button.cancel" /></Button>
								</div>
								: <div><Button variant="raised" color="primary" className="text-white" onClick={() => this.onSubmitPostEditDetailForm()}><IntlMessages id="button.update" /></Button>{' '}
									<Button variant="raised" className="btn-danger text-white" onClick={this.toggleEditPostModal}><IntlMessages id="button.cancel" /></Button></div>
							}
						</ModalFooter>
					</Modal>
				}
				<Snackbar
					anchorOrigin={{
						vertical: 'top',
						horizontal: 'center',
					}}
					open={this.state.snackbar}
					onClose={() => this.setState({ snackbar: false })}
					autoHideDuration={2000}
					message={<span id="message-id">{this.state.snackbarMessage}</span>}
				/>
			</div>
		);
	}
}
