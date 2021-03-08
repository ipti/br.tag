const styles = (theme) => ({
  container: {
    display: 'flex',
    flexDirection: 'column',
    alignItems: 'center',
    justifyContent: 'space-around',
    maxWidth: 420,

    '& > svg': {
      width: 290,
      height: 300
    },

    [theme.breakpoints.down('sm')]: {
      maxWidth: 280,

      '& > svg': {
        width: 220,
        height: 230
      }
    }
  },
  message: {
    marginBottom: 25
  },
  button: {
    width: 200,
    marginBottom: 40
  }
});

export { styles };
