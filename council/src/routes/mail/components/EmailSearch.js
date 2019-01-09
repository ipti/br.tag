/**
 * Eamil Search
 */
import React, { Component } from 'react';
import { FormGroup, Input } from 'reactstrap';
import { connect } from 'react-redux';

// actions
import { searchEmail, updateSearchEmail } from 'Actions';

class EmailSearch extends Component {

    /**
     * On Search Email
     */
    onSearchEmail(e) {
        this.props.updateSearchEmail(e.target.value);
        this.props.searchEmail(e.target.value);
    }

    render() {
        const { searchEmailText } = this.props;
        return (
            <FormGroup className="mb-0 w-30">
                <Input
                    type="text"
                    name="search"
                    id="search-todo"
                    placeholder="Search Mail"
                    onChange={(e) => this.onSearchEmail(e)}
                    value={searchEmailText}
                />
            </FormGroup>
        );
    }
}

// map state to props
const mapStateToProps = ({ emailApp }) => {
    return emailApp;
}

export default connect(mapStateToProps, {
    updateSearchEmail,
    searchEmail
})(EmailSearch);