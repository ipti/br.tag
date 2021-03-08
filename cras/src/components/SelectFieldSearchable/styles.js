import { colors, typography } from '../../styles';

const styles = () => ({
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
  }
});

export { styles };
