import { typography, colors } from '../../styles';

const styles = {
  menuItem: {
    display: 'flex',
    flexDirection: 'column',
    padding: 5,
    fontFamily: typography.types.light
  },
  link: {
    display: 'inline-flex',
    alignItems: 'center',
    color: ({ active }) => (active ? colors.blue : colors.gray),
    textDecoration: 'none',
    fontSize: typography.font.extraSmall + 2,

    '& > svg': {
      marginLeft: 5,
      marginRight: 10,
      fill: ({ active }) => (active ? colors.blue : colors.gray),

      '& *': {
        stroke: ({ active }) => (active ? colors.blue : colors.gray)
      }
    }
  },
  submenu: {
    listStyleType: 'none',
    marginTop: -10,

    '& > li > a': {
      color: colors.grayClear1,
      fontSize: typography.font.extraSmall
    }
  },
  outlined: {
    borderLeft: ({ active }) => (active ? `solid 3px ${colors.blue}` : 'unset')
  }
};

export { styles };
