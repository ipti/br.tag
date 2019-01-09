import React from 'react';
import Button from '@material-ui/core/Button';

export default class Anexo extends React.Component{
    
    constructor(props){
        super(props);
    }

    render(){
        const files = this.props.files == null ? []: this.props.files;
        const itens = files.map((file, key) => 
            <li key={key} className="list-inline-item border-bottom-0">
                <Button variant="fab" color="primary" className="text-white" mini aria-label="Download">
                    <a href={`/web/${file}`} download> <i className="text-white zmdi zmdi-download"></i></a>
                </Button>
            </li>
        );
        if(files.length == 0){
            return <div></div>
        }
        return(
            <div className="mt-20">
                <h4>Anexos</h4>
                <ul id="anexos" className="list-inline mt-10"> 
                    {itens}
                </ul>
            </div>
        )
    }
}