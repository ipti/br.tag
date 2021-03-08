import { colors, typography } from '../../styles';

const styles = {
  buttonGroup: {
    '& .MuiToggleButton-root': {
      borderRadius: 10,
      borderColor: 'transparent',
      padding: '5px 15px',
      textTransform: 'none',
      fontFamily: typography.types.regular,
      fontSize: typography.font.extraSmall,
      backgroundColor: colors.grayClear2,
      '&:first-child': {
        borderTopRightRadius: 0,
        borderBottomRightRadius: 0,
        borderRight: `solid 2px ${colors.grayClear1}`
      },
      '&:last-child': {
        borderTopLeftRadius: 0,
        borderBottomLeftRadius: 0
      },
      '&:hover': {
        backgroundColor: colors.yellow
      },
      '&.Mui-selected': {
        backgroundColor: colors.yellow
      },
      '& .MuiToggleButton-label': {
        color: colors.gray,
        '& img': {
          marginRight: 6
        },
        '&:hover': {
          color: colors.grayDark
        }
      }
    }
  },
  '@media(max-width: 768px)': {
    buttonGroup: {
      '& .MuiToggleButton-root': {
        fontSize: 10,
        '& img': {
          width: 12
        }
      }
    }
  }
};

export { styles };
