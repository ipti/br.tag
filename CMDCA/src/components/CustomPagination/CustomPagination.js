import React, { Component } from 'react';
import ReactPaginate from 'react-paginate';
import PropTypes from 'prop-types';
import './style.css';

class CustomPagination extends Component{

    render(){
        const {totalPages, currentPage} = this.props;
        return (
            <div className="mb-20">
                <ReactPaginate
                    previousLabel={'anterior'}
                    nextLabel={'próxima'}
                    breakLabel={'...'}
                    breakClassName={'break-me'}
                    pageCount={totalPages}
                    forcePage={currentPage}
                    marginPagesDisplayed={2}
                    pageRangeDisplayed={5}
                    onPageChange={this.props.handlePageChange}
                    containerClassName={'pagination'}
                    subContainerClassName={'pages pagination'}
                    activeClassName={'active'}
                    />
            </div>
        );
    }
}

CustomPagination.propTypes = {
    totalItens: PropTypes.number.isRequired,
    perPage: PropTypes.number.isRequired,
    totalPages: PropTypes.number.isRequired,
    currentPage: PropTypes.number.isRequired,
    handlePageChange: PropTypes.func.isRequired,
};

export default CustomPagination;