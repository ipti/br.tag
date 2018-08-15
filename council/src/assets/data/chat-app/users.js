// chat app users
export default [
    {
        id: 2,
        first_name: 'Elisabeth',
        last_name: 'Ortega',
        photo_url: require('Assets/avatars/user-1.jpg'),
        last_chat_date: 'yesterday',
        isActive: true,
        status: "online",
        last_chat: 'Ut vel consectetur ligula, non tincidunt elit. Nulla pellentesque finibus consequat.',
        new_message_count: 5,
        isSelectedChat: true,
        previousChats: [
            {
                message: 'Sed mollis, mi in malesuada semper, ipsum nulla luctus sem',
                sent: '12:47 PM',
                isAdmin: false
            },
            {
                message: 'Vivamus aliquet ligula augue, et suscipit mauris sollicitudin ',
                sent: '12:49 PM',
                isAdmin: true
            },
            {
                message: 'Phasellus in felis posuere, fringilla ligula eget, tristique diam',
                sent: '12:51 PM',
                isAdmin: true
            },
            {
                message: 'Ut vel consectetur ligula, non tincidunt elit. Nulla pellentesque finibus consequat.',
                sent: '12:55 PM',
                isAdmin: false
            }
        ]
    },
    {
        id: 3,
        first_name: 'Madeleine',
        last_name: 'Dean',
        photo_url: require('Assets/avatars/user-2.jpg'),
        last_chat_date: 'today',
        isActive: true,
        status: "online",
        last_chat: 'Sed elementum vel ex ullamcorper egestas. Phasellus laoreet nec sem et tempus. Integer pellentesque sapien augue',
        new_message_count: 10,
        isSelectedChat: false,
        previousChats: [
            {
                message: 'Maecenas lacus nunc, condimentum sed arcu eget,',
                sent: '12:47 PM',
                isAdmin: false
            },
            {
                message: 'Duis ullamcorper laoreet nulla, sed mollis urna semper nec. Cras ac bibendum neque.',
                sent: '12:49 PM',
                isAdmin: true
            },
            {
                message: 'Pellentesque interdum aliquam nunc quis viverra. Morbi placerat massa eget neque feugiat, sit amet porta nibh vehicula.',
                sent: '12:51 PM',
                isAdmin: true
            },
            {
                message: 'Sed elementum vel ex ullamcorper egestas. Phasellus laoreet nec sem et tempus. Integer pellentesque sapien augue',
                sent: '12:55 PM',
                isAdmin: false
            }
        ]
    },
    {
        id: 4,
        first_name: 'Lindsey',
        last_name: 'Cruz',
        photo_url: require('Assets/avatars/user-3.jpg'),
        last_chat_date: 'yesterday',
        isActive: false,
        status: "Last seen yesterday",
        last_chat: 'Maecenas commodo eros ac libero ultricies, eu iaculis diam commodo',
        new_message_count: 0,
        isSelectedChat: false,
        previousChats: [
            {
                message: 'Quisque arcu massa, iaculis et sollicitudin id, sollicitudin non leo. Ut placerat mi rutrum enim hendrerit vestibulum. ',
                sent: '12:47 PM',
                isAdmin: false
            },
            {
                message: 'Suspendisse nec orci odio. Donec rutrum ipsum sit amet lorem dignissim, non maximus nibh porta.',
                sent: '12:49 PM',
                isAdmin: true
            },
            {
                message: 'Mauris quis metus urna. Proin vel magna in sem scelerisque congue eget non libero.',
                sent: '12:51 PM',
                isAdmin: true
            },
            {
                message: 'Phasellus turpis erat, consectetur vel fermentum malesuada, volutpat in elit. Quisque egestas lorem lectus, quis porta dui vestibulum in.',
                sent: '12:55 PM',
                isAdmin: false
            }
        ]
    },
    {
        id: 5,
        first_name: 'Erik',
        last_name: 'Schneider',
        photo_url: require('Assets/avatars/user-4.jpg'),
        last_chat_date: '3 days ago',
        isActive: false,
        status: "Last seen 1 day ago",
        last_chat: 'Proin hendrerit, nulla vel tincidunt rutrum, libero ante ornare justo, non luctus massa ligula ut orci. ',
        new_message_count: 0,
        isSelectedChat: false,
        previousChats: [
            {
                message: 'Pellentesque mollis neque vel enim pellentesque, vel scelerisque erat ullamcorper.',
                sent: '12:47 PM',
                isAdmin: false
            },
            {
                message: 'Cras vel tellus neque. Maecenas dapibus velit vitae ',
                sent: '12:47 PM',
                isAdmin: false
            },
            {
                message: 'Proin hendrerit, nulla vel tincidunt rutrum, libero ante ornare justo, non luctus massa ligula ut orci. ',
                sent: '12:50 PM',
                isAdmin: true
            }
        ]
    },
    {
        id: 6,
        first_name: 'Philip',
        last_name: 'Phillips',
        photo_url: require('Assets/avatars/user-5.jpg'),
        last_chat_date: '1 week ago',
        isActive: false,
        "status": "Last seen 2 days ago",
        last_chat: 'Phasellus ultrices porttitor orci, eget venenatis lorem ultrices quis. Proin eget magna tellus. Ut a magna feugiat, facilisis',
        new_message_count: 0,
        isSelectedChat: false,
        previousChats: [
            {
                message: 'Vivamus vitae nibh ullamcorper, vulputate mauris et, porta turpis. Donec non mauris nec felis aliquam tristique eget eu mauris',
                sent: '12:47 PM',
                isAdmin: false
            },
            {
                message: ' Nunc eget nulla nisl. Donec accumsan ex vel risus ornare, ac tincidunt risus auctor. Curabitur hendrerit felis tortor, ac ',
                sent: '12:49 PM',
                isAdmin: true
            },
            {
                message: 'Sed risus est, scelerisque pharetra leo vitae, efficitur laoreet elit. Ut in nunc rutrum ligula auctor venenatis quis',
                sent: '12:51 PM',
                isAdmin: true
            },
            {
                message: 'Phasellus ultrices porttitor orci, eget venenatis lorem ultrices quis. Proin eget magna tellus. Ut a magna feugiat, facilisis',
                sent: '12:55 PM',
                isAdmin: false
            }
        ]
    },
    {
        id: 7,
        first_name: 'Sabrina',
        last_name: 'White',
        photo_url: require('Assets/avatars/user-6.jpg'),
        last_chat_date: '2 weeks ago',
        isActive: true,
        status: "online",
        last_chat: 'Curabitur fermentum felis ac eros convallis ornare. ',
        new_message_count: 0,
        isSelectedChat: false,
        previousChats: [
            {
                message: 'Vivamus vitae nibh ullamcorper, vulputate mauris et, porta turpis. Donec non mauris nec felis aliquam tristique eget eu mauris',
                sent: '12:47 PM',
                isAdmin: false
            },
            {
                message: ' Nunc eget nulla nisl. Donec accumsan ex vel risus ornare, ac tincidunt risus auctor. Curabitur hendrerit felis tortor, ac ',
                sent: '12:49 PM',
                isAdmin: true
            },
            {
                message: 'Sed risus est, scelerisque pharetra leo vitae, efficitur laoreet elit. Ut in nunc rutrum ligula auctor venenatis quis',
                sent: '12:51 PM',
                isAdmin: true
            },
            {
                message: 'Phasellus ultrices porttitor orci, eget venenatis lorem ultrices quis. Proin eget magna tellus. Ut a magna feugiat, facilisis',
                sent: '12:55 PM',
                isAdmin: false
            }
        ]
    },
    {
        id: 8,
        first_name: 'Nicolas',
        last_name: 'Wolf',
        photo_url: require('Assets/avatars/user-7.jpg'),
        last_chat_date: '2 weeks ago',
        isActive: false,
        status: "Last seen yesterday",
        last_chat: ' Donec convallis sem in ex luctus, tempor commodo massa porta.',
        new_message_count: 0,
        isSelectedChat: false,
        previousChats: [
            {
                message: 'Vivamus vitae nibh ullamcorper, vulputate mauris et, porta turpis. Donec non mauris nec felis aliquam tristique eget eu mauris',
                sent: '12:47 PM',
                isAdmin: false
            },
            {
                message: ' Nunc eget nulla nisl. Donec accumsan ex vel risus ornare, ac tincidunt risus auctor. Curabitur hendrerit felis tortor, ac ',
                sent: '12:49 PM',
                isAdmin: true
            },
            {
                message: 'Sed risus est, scelerisque pharetra leo vitae, efficitur laoreet elit. Ut in nunc rutrum ligula auctor venenatis quis',
                sent: '12:51 PM',
                isAdmin: true
            },
            {
                message: 'Phasellus ultrices porttitor orci, eget venenatis lorem ultrices quis. Proin eget magna tellus. Ut a magna feugiat, facilisis',
                sent: '12:55 PM',
                isAdmin: false
            }
        ]
    }
]