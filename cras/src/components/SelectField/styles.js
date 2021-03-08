import { colors, typography } from '../../styles';

const styles = (theme) => ({
  withSelectBig: {
    minWidth: 290
  },
  withSelectSmall: {
    minWidth: 90
  },
  formControl: {
    float: 'left'
  },
  selectEmpty: {
    marginTop: theme.spacing(2)
  },
  label: {
    fontFamily: typography.types.regular,
    fontSize: typography.font.medium
  },
  textError: {
    fontSize: typography.font.extraSmall,
    color: colors.red,
    display: 'block',
    marginTop: 5
  },
  selectRoot: {
    float: 'left',
    margin: '0 6px',
    '& .MuiSelect-root': {
      fontFamily: typography.types.regular,
      fontSize: typography.font.extraSmall,
      color: colors.gray,
      padding: '9px 14px'
    },
    '& .MuiOutlinedInput-root': {
      borderRadius: 8
    }
  },
  selectFilter: {
    fontFamily: typography.types.regular,
    '& .select__control': {
      borderRadius: 10,
      paddingTop: 2,
      paddingBottom: 2,
      border: `solid 1px ${colors.gray}`
    },
    '& .select__indicator-separator': {
      backgroundColor: 'transparent !important',
      color: colors.gray
    }
  },
  labelFilter: {
    margin: '10px 0',
    fontFamily: typography.types.regular,
    color: colors.grayClear1,
    fontSize: typography.font.medium
  },
  '@media(max-width: 1440px)': {
    buttonFilter: {
      padding: '5px 10px'
    }
  },
  '@media(min-width: 960px and max-width: 1023px)': {
    withSelectSmall: {
      marginLeft: 46
    }
  },
  '@media(max-width: 768px)': {
    formControl: {
      minWidth: 90
    }
  }
});

export { styles };
