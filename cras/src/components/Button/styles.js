import { typography, colors } from '../../styles';

const sizes = {
  small: 35,
  medium: 45,
  large: 55
};

const colorPallete = {
  primary: {
    main: colors.blue,
    contrast: colors.white
  },
  secondary: {
    main: colors.white,
    contrast: colors.blue
  }
};

const variants = {
  outlined: {
    border: (color) => `solid 1px ${colorPallete?.[color]?.contrast}`
  },
  contained: {
    border: () => `none`
  },
  text: {
    border: () => `none`
  }
};

const styles = {
  button: {
    backgroundColor: ({ color }) => colorPallete?.[color]?.main ?? colorPallete.primary.main,
    border: ({ variant, color }) => variants?.[variant]?.border?.(color) ?? 'none',
    borderRadius: '5px',
    color: ({ color }) => colorPallete?.[color]?.contrast ?? colorPallete.primary.contrast,
    fontSize: ({ size }) => typography.font?.[size] ?? typography.font.medium,
    height: ({ size }) => sizes?.[size] ?? sizes.medium,
    fontFamily: typography.types.regular,
    cursor: 'pointer',
    transition: 'ease-in',
    outline: 'none'
  }
};

export default styles;
