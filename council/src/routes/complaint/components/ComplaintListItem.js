/**
 * Checkbox List Component
 */
import React, { Component } from 'react';
import List from '@material-ui/core/List';
import ListItem from '@material-ui/core/ListItem';
import Hidden from '@material-ui/core/Hidden';
import ListItemSecondaryAction from '@material-ui/core/ListItemSecondaryAction';
import ListItemText from '@material-ui/core/ListItemText';
import Chip from '@material-ui/core/Chip';
import Checkbox from '@material-ui/core/Checkbox';
import IconButton from '@material-ui/core/IconButton';
import { Scrollbars } from 'react-custom-scrollbars';
import EmailDetail from 'Routes/mail/components/EmailDetail';
import { Col} from 'reactstrap';
// intl messages
import IntlMessages from 'Util/IntlMessages';

// rct card box
import RctCollapsibleCard from 'Components/RctCollapsibleCard/RctCollapsibleCard';

class ComplaintListItem extends Component {
    constructor(props) {
		super(props);
		this.state = {
			listItems: this.props.listItems
        };
        this.handleToggleCheckItem = this.handleToggleCheckItem.bind(this);
        this.handleToggleClickItem = this.handleToggleClickItem.bind(this);
    }
    
    handleToggleCheckItem(key) {
        let items = this.state.listItems;
        items[key].checked = !items[key].checked;
        this.setState({ listItems: items });
    }

    handleToggleClickItem(key) {
       this.props.history.push('/app/complaint/view/' + key);
    }

    handleToggleEditItem(key, status) {
        if(status == '1'){
            alert('Antes de editar formalize a denúncia.');
            return;
        }
        this.props.history.push('/app/complaint/update/' + key);
    }

    render() {
        return (
            <RctCollapsibleCard >
                <Scrollbars className="rct-scroll" autoHeight autoHeightMin={100} autoHeightMax={2260} autoHide>
                    <List>
                        {this.state.listItems.map((data, key) => (
                            <ListItem className="px-0 b-bottom" key={key}>
                                <div className="container row">
                                    <div className="col-12 col-md-4 d-flex align-items-center">
                                        <IconButton  mini onClick={() => this.handleToggleClickItem(data.id)}> <i className={`zmdi zmdi-label-alt zmdi-hc-md label-${data.status}`} style={{marginRight:'8px', marginBottom:'8px'}}></i></IconButton>
                                        <h5 style={{textTransform:"uppercase"}}>{data.title} </h5>
                                    </div>
                                    <div className="col-10 col-md-7 d-flex align-items-center">
                                        <Chip className="chip-outline-primary mr-10 mb-10" label={data.typeName} />
                                        <Chip className="chip-outline-sencondary mr-10 mb-10" label={data.placeName} />
                                        <Hidden mdDown>
                                            <Chip className="chip-outline-secondary mr-10 mb-10" label={data.forwardDate} />
                                        </Hidden>
                                    </div>
                                    <div className="col-2 col-md-1 d-flex align-items-center">
                                        <IconButton color="primary" mini onClick={() => this.handleToggleEditItem(data.id, data._status)}><i className={`zmdi zmdi-edit zmdi-hc-md`}></i></IconButton>
                                    </div>
                                </div>
                            </ListItem>
                        ))}
                    </List>
                </Scrollbars>
            </RctCollapsibleCard>
        );
    }
}

export default ComplaintListItem;