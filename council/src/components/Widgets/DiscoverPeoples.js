/**
 * Discover Peoples
 */
import React, { Component } from 'react';
import classnames from 'classnames';
import List from '@material-ui/core/List';
import ListItem from '@material-ui/core/ListItem';

// api
import api from 'Api';

class DiscoverPeoplesWidget extends Component {

    state = {
        people: null
    }

    componentDidMount() {
        this.getDiscoverPeoples();
    }

    // get discover peoples
    getDiscoverPeoples() {
        api.get('discoverPeoples.js')
            .then((response) => {
                this.setState({ people: response.data })
            })
            .catch(error => {
                // error handling
            })
    }

    /**
     * Function to follow and unfolow people
     * @param {object} data
     */
    togglePeopleFollow(key) {
        let people = this.state.people;
        people[key].status = !people[key].status;
        this.setState({ people });
    }

    render() {
        const { people } = this.state;
        return (
            <List>
                {people && people.map((data, key) => (
                    <ListItem button key={key} onClick={() => this.togglePeopleFollow(key)}>
                        <div className="d-flex justify-content-between w-100">
                            <div className="d-flex align-items-center">
                                <div className="media">
                                    <div className="media-left mr-20">
                                        <img src={data.photo_url} alt="user profile" className="rounded-circle img-fluid" width="55" height="55" />
                                    </div>
                                    <div className="media-body pt-15">
                                        <p className="mb-0 text-muted">{data.name}</p>
                                    </div>
                                </div>
                            </div>
                            <div className="d-flex align-items-center">
                                <span className={classnames('badge badge-pill badge-lg', { 'badge-info': data.status, 'badge-dark': !data.status })}>{data.status ? 'Following' : 'Follow'}</span>
                            </div>
                        </div>
                    </ListItem>
                ))}
            </List>
        );
    }
}

export default DiscoverPeoplesWidget;
