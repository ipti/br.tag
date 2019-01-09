import React from 'react';
import List from '@material-ui/core/List';
import ListItem from '@material-ui/core/ListItem';
import ListItemText from '@material-ui/core/ListItemText';
import Avatar from '@material-ui/core/Avatar';
import AppBar from '@material-ui/core/AppBar';
import Toolbar from '@material-ui/core/Toolbar';
import Typography from '@material-ui/core/Typography';

// data
import users from 'Assets/data/chat-app/users';

// helpers
import { textTruncate } from 'Helpers/helpers';

const ChatSidebar = () => (
   <div className="chat-sidebar rct-customizer">
      <AppBar position="static" color="primary">
         <Toolbar>
            <Typography variant="title" color="inherit">
               Chat
            </Typography>
         </Toolbar>
      </AppBar>
      <List>
         {users.map((user, key) => (
            <ListItem key={key} button>
               <Avatar src={user.photo_url} />
               <ListItemText
                  primary={user.first_name + ' ' + user.last_name}
                  secondary={textTruncate(user.last_chat, 16)}
               />
            </ListItem>
         ))}
      </List>
   </div>
);

export default ChatSidebar;
