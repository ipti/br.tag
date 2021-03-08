import { colors, typography } from '../../styles';

const styles = (theme) => ({
  boxCardFields: {
    marginTop: 70,
    display: 'block'
  },
  boxTypeStage: {
    display: 'block',
    marginTop: 20,
    marginBottom: 40
  },
  spanStage: {
    backgroundColor: colors.grayClear2,
    padding: '2px 20px',
    borderRadius: 20,
    marginLeft: 15
  },
  textField: {
    width: '90%',
    '& input': {
      padding: '12px 14px'
    },

    '& .MuiInputBase-formControl': {
      borderRadius: 8
    }
  },
  boxDateStart: {
    display: 'block',
    '& .MuiFormLabel-root': {
      marginBottom: 10
    }
  },
  boxDateEnd: {
    display: 'block',
    marginTop: 50,
    '& .MuiFormLabel-root': {
      marginBottom: 10
    }
  },
  withLabe1: {
    margin: '4px 0px'
  },
  withLabe2: {
    margin: '0 8px'
  },
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
