/**
 * Login Page
 */

import React, { Component } from 'react';
import { connect } from 'react-redux';
import Button from '@material-ui/core/Button';
import AppBar from '@material-ui/core/AppBar';
import Toolbar from '@material-ui/core/Toolbar';
import { Link } from 'react-router-dom';
import { Form, FormGroup, Input } from 'reactstrap';
import LinearProgress from '@material-ui/core/LinearProgress';
import QueueAnim from 'rc-queue-anim';
import api from 'Api';

// app config
import AppConfig from 'Constants/AppConfig';

// redux action
import {
  signinUserInFirebase,
  signinUserWithFacebook,
  signinUserWithGoogle,
  signinUserWithGithub,
  signinUserWithTwitter
} from 'Actions';

class Signin extends Component {

  state = {
    username: '',
    password: ''
  }

  /**
   * On User Login
   */
  onUserLogin() {
    if (this.state.username !== '' && this.state.password !== '') {
      api.post('/user/login', this.state)
        .then(function(response){
          if(typeof response.data.status !== 'undefined' && response.data.status == '1'){
            sessionStorage.setItem('user', response.data.data._id);
            sessionStorage.setItem('token', response.data.data.access_token);
            this.props.history.push('/app/complaint/list');
          }
        }.bind(this))
        .catch(function(error){
          console.log(error);
        });
    }
  }

  /**
   * On User Sign Up
   */
  onUserSignUp() {
    this.props.history.push('/citizen/form');
  }

  render() {
    const { username, password } = this.state;
    const { loading } = this.props;
    return (
      <QueueAnim type="bottom" duration={2000}>
        <div className="rct-session-wrapper">
          {loading &&
            <LinearProgress />
          }
          <AppBar position="static" className="session-header">
            <Toolbar>
              <div className="container">
                <div className="d-flex justify-content-between">
                  <div className="session-logo">
                    <Link to="/">
                      <img src={require('Assets/img/appLogoText.png')} alt="session-logo" className="img-fluid" width="110" height="35" />
                    </Link>
                  </div>
                  <div>
                    <Link to="../citizen/form">
                      <Button variant="raised" className="btn-light" onClick={() => this.onUserSignUp()}>Denunciar ao Conselho</Button>
                    </Link>
                  </div>
                </div>
              </div>
            </Toolbar>
          </AppBar>
          <div className="session-inner-wrapper">
            <div className="container">
              <div className="row justify-content-md-center">
                <div className="col-sm-5 col-md-5 col-lg-6">
                  <div className="session-body text-center">
                    <div className="session-head mb-30">
                      <h2 className="font-weight-bold">Bem-vindo ao {AppConfig.brandName}</h2>
                      <p className="mb-0">Informe sua credencial de acesso</p>
                    </div>
                    <Form>
                      <FormGroup className="has-wrapper">
                        <Input type="mail" value={username} name="user-mail" id="user-mail" autoComplete="off" className="has-input input-lg" placeholder="E-mail" onChange={(event) => this.setState({ username: event.target.value })} />
                        <span className="has-icon"><i className="ti-email"></i></span>
                      </FormGroup>
                      <FormGroup className="has-wrapper">
                        <Input value={password} type="Password" name="user-pwd" id="pwd" className="has-input input-lg" placeholder="Senha" onChange={(event) => this.setState({ password: event.target.value })} />
                        <span className="has-icon"><i className="ti-lock"></i></span>
                      </FormGroup>
                      <FormGroup className="mb-15">
                        <Button
                          color="primary"
                          className="btn-block text-white w-100"
                          variant="raised"
                          size="large"
                          onClick={() => this.onUserLogin()}>
                          Entrar
                        </Button>
                      </FormGroup>
                    </Form>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </QueueAnim>
    );
  }
}

// map state to props
const mapStateToProps = ({ authUser }) => {
  const { user, loading } = authUser;
  return { user, loading }
}

export default connect(mapStateToProps, {
  signinUserInFirebase,
  signinUserWithFacebook,
  signinUserWithGoogle,
  signinUserWithGithub,
  signinUserWithTwitter
})(Signin);
