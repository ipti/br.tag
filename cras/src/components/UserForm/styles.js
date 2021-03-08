import { colors, typography } from '../../styles';

export const styles = {
  icon: {
    color: colors.gray
  },
  formGroup: {
    display: 'flex',
    marginTop: 20,
    marginBottom: 20,

    '& > label': {
      color: colors.grayClear1,
      marginBottom: 10
    },

    '& input': {
      height: 9
    },

    '& .MuiFormControl-root': {
      width: 350,
      maxWidth: '100%'
    }
  },
  formGroupButton: {
    display: 'flex',
    flexDirection: 'row',
    justifyContent: 'flex-end',
    marginBottom: 20,

    '& > button': {
      marginLeft: 15,
      paddingLeft: 15,
      paddingRight: 15,
      fontSize: typography.font.small,
      height: 40
    }
  },
  error: {
    color: colors.red
  }
};
