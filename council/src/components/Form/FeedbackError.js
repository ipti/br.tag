import React from 'react';
const FeedbackError = (props) =>{

    return(
        <div className="invalid-feedback" style={{display: props.errors.length > 0 ? 'block': 'none'}} >
            {
                props.errors.map((error, key) => <span style={{display: 'block'}} key={key} > {error.message} </span> )
            }
        </div>
    );
}

export default FeedbackError;