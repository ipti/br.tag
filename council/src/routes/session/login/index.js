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
import RctSectionLoader from 'Components/RctSectionLoader/RctSectionLoader';
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
    password: '',
    loader: false
  }

  /**
   * On User Login
   */
  onUserLogin() {
    if (this.state.username !== '' && this.state.password !== '') {
      sessionStorage.clear();
      this.setState({loader: true});
      api.post('/user/login', this.state)
        .then(function(response){
          if(typeof response.data.status !== 'undefined' && response.data.status == '1'){
            let data = response.data.data;
            sessionStorage.setItem('user', data._id);
            sessionStorage.setItem('user_name', data.name);
            sessionStorage.setItem('user_email', data.email);
            sessionStorage.setItem('token', data.access_token);
            sessionStorage.setItem('institution', data.institution);
            sessionStorage.setItem('institution_type', data.institution_type);
            this.props.history.push('/app/complaint/list');
          }
          else{
            this.setState({loader: false});
            alert(response.data.message);
          }
        }.bind(this))
        .catch(function(error){
          this.setState({loader: false});
          console.log(error);
        });
    }
  }

  onUserLogout(){
    this.setState({loader: true});
    api.post(`/user/logout`, {token: sessionStorage.getItem('token')})
        .then(function(response){
          if(typeof response.data.status !== 'undefined' && response.data.status == '1'){
            sessionStorage.clear();
            this.setState({loader: false});
            this.props.history.push('/session/login');
          }
        }.bind(this))
        .catch(function(error){
          sessionStorage.clear();
          this.setState({loader: false});
          this.props.history.push('/session/login');
        });
  }

  componentDidMount(){
    if(this.props.location.pathname.indexOf('logout') >= 0){
      this.onUserLogout();
    }else{
      sessionStorage.clear();
    }
  }

  /**
   * On User Sign Up
   */
  onUserSignUp() {
    this.props.history.push('/citizen/form');
  }

  onUserFollow() {
    this.props.history.push('/citizen/follow');
  }

  render() {
    const { username, password } = this.state;
    const { loading } = this.props;

    if (this.state.loader) {
			return (
				<RctSectionLoader />
			)
    }
    
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
                    <Link to="../citizen/follow">
                      <Button variant="raised" className="btn-light ml-10" onClick={() => this.onUserFollow()}>Acompanhar Denúncia</Button>
                    </Link>
                    <Link to="../food_action/form">
                      <Button variant="raised" className="btn-light ml-10" onClick={() => this.onUserFollow()}>Ação de Alimentos</Button>
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
