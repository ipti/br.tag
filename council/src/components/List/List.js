import React, { Component, Fragment } from 'react';
import PropTypes from 'prop-types';

class List extends Component{

    render(){
        return(
            <Fragment>
                <div className="row mx-0">
                    {
                        this.props.items.map((item, key) =>  
                            <Fragment key={key}>
                                {item}
                            </Fragment >
                        )
                    }
                </div>
            </Fragment>
        )
    }

}

List.propTypes = {
    items: PropTypes.array.isRequired,
};


export default List;