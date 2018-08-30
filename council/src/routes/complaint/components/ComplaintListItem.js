/**
 * Checkbox List Component
 */
import React, { Component } from 'react';
import List from '@material-ui/core/List';
import ListItem from '@material-ui/core/ListItem';
import ListItemSecondaryAction from '@material-ui/core/ListItemSecondaryAction';
import ListItemText from '@material-ui/core/ListItemText';
import Checkbox from '@material-ui/core/Checkbox';
import IconButton from '@material-ui/core/IconButton';
import { Scrollbars } from 'react-custom-scrollbars';
import EmailDetail from 'Routes/mail/components/EmailDetail';

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
        items[key].status = !items[key].status;
        this.setState({ listItems: items });
    }

    handleToggleClickItem(key) {
       this.props.history.push('/app/complaint/view/' + key);
    }

    render() {
        return (
            <RctCollapsibleCard >
                <Scrollbars className="rct-scroll" autoHeight autoHeightMin={100} autoHeightMax={2260} autoHide>
                    <List>
                        {this.state.listItems.map((data, key) => (
                            <ListItem className="px-0" button onClick={() => this.handleToggleClickItem(key)} key={key}>
                                <IconButton><i className={`zmdi zmdi-label-alt zmdi-hc-md label-${data.statusColor}`}></i></IconButton>
                                <Checkbox onClick={() => this.handleToggleCheckItem(key)} key={key} color="primary" checked={data.status} />
                                <ListItemText primary={data.title} secondary={data.description} />
                            </ListItem>
                        ))}
                    </List>
                </Scrollbars>
            </RctCollapsibleCard>
        );
    }
}

export default ComplaintListItem;