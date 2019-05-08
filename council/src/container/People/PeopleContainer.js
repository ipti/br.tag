import React, { Component, Fragment } from 'react';
import {Link} from 'react-router';
import People from 'Components/People/People';
import CustomPagination from 'Components/CustomPagination/CustomPagination';
import Card from 'Components/People/Card';
import RctSectionLoader from 'Components/RctSectionLoader/RctSectionLoader';
import { connect } from 'react-redux';
import { getPeople } from 'Actions';
import 'Assets/css/people/style.css';


class PeopleContainer extends Component{

    constructor(props){
        super(props);
        this.state = {
            peoples: [],
            pagination: {
                currentPage: 1,
                perPage: 15,
                totalItens: 0,
                totalPages: 1
            },
            loading: true
        }
    }

    pageChange = async (page) =>{
        const nextPage = page.selected + 1;
        await this.props.getPeople(nextPage);
    }

    componentDidMount = async () =>{
        await this.props.getPeople();
    }

    loadPeople = () =>{
        return this.props.peoples.map((people) =>{
            return <Card id={people._id} history={this.props.history} name={people.name} birthday={people.birthday} />
        });
    }
    
    render(){
        const peoples = this.loadPeople();
        return(
            <Fragment>
                {
                    this.props.loading ? 
                    (
                        <RctSectionLoader />
                    )
                    :
                    (
                        <Fragment>
                            <People peoples={peoples} />
                            <CustomPagination totalItens={this.props.pagination.totalItens} perPage={this.props.pagination.perPage} totalPages={this.props.pagination.totalPages} currentPage={this.props.pagination.currentPage} handlePageChange={this.pageChange} />
                        </Fragment>
                    )
                }
            </Fragment>
        );
    }

}

const mapStateToProps = ({ people }) => {
    return people;
 };

export default connect(mapStateToProps, {
    getPeople
 })(PeopleContainer)