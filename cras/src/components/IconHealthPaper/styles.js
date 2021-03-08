import { colors } from '../../styles';

const styles = {
  boxIcon: {
    borderRadius: 6,
    color: colors.white,
    padding: 7,
    width: 28,
    height: 24,
    textAlign: 'center',
    background: `${colors.blue}`,

    '& > svg': {
      maxHeight: 24
    }
  }
};

export { styles };
