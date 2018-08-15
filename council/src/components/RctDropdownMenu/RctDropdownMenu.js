import React,{Component} from 'react';
import Menu from '@material-ui/core/Menu';

//Components
import RctDropdownItem from './RctDropdownItem';

class RctDropdownMenu extends Component {
   state = {
      anchorEl: null,
   };

   handleClick = event => {
      this.setState({ anchorEl: event.currentTarget });
   };

   handleClose = () => {
      this.setState({ anchorEl: null });
   };

   render() {
      const { anchorEl } = this.state;
      const { children } = this.props;
      return (
         <div>
            <div
               aria-owns={anchorEl ? 'rct-dropdown-menu' : null}
               aria-haspopup="true"
               onClick={this.handleClick}
            >
               {children}
            </div>
            <Menu
               id="rct-dropdown-menu"
               anchorEl={anchorEl}
               open={Boolean(anchorEl)}
               onClose={this.handleClose}
            >
               <RctDropdownItem>
                 {children}
               </RctDropdownItem>
            </Menu>
         </div>
      );
   }
}

export default RctDropdownMenu;