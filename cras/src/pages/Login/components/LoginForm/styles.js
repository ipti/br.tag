import { colors } from '../../../../styles';

export const styles = {
  icon: {
    color: colors.gray
  },
  formGroup: {
    display: 'flex',
    alignItems: 'center',
    margin: 15,

    '& .MuiFormControl-root': {
      width: 350,
      maxWidth: '100%'
    },

    '& > button': {
      alignSelf: 'center',
      marginTop: 40,
      width: 200
    }
  },
  error: {
    color: colors.red
  }
};
