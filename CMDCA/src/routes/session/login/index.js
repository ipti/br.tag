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
    email: '',
    password: '',
    loader: false
  }

  /**
   * On User Login
   */
  async onUserLogin() {
    if (this.state.username !== '' && this.state.password !== '') {
      localStorage.clear();
      this.setState({loader: true});
      const info = {email:this.state.email, password:this.state.password};
      await api.post('/auth/authenticate', info)
        .then(function(response){
          
          if(typeof response.status !== 'undefined'){
            let data = response.data;
    
            localStorage.setItem('user', data.user._id);
            localStorage.setItem('user_name', data.user.name);
            localStorage.setItem('user_email', data.user.email);
            localStorage.setItem('token', data.token);
            //localStorage.setItem('institution', data.institution);
            //localStorage.setItem('institution_type', data.institution_type);
            this.props.history.push('/app/home/index');
          }
          else{
            this.setState({loader: false});
            alert(response.data);
          }
        }.bind(this))
        .catch((error)=>{
          this.setState({loader: false});
          alert(error.response.data.error);
          this.props.history.push('/session/login');
          
        });
    }
  }

  onUserLogout(){
    localStorage.clear();
    this.props.history.push('/session/login');
    /*this.setState({loader: true});
    api.post(`/user/logout`, {token: localStorage.getItem('token')})
        .then(function(response){
          if(typeof response.data.status !== 'undefined' && response.data.status == '1'){
            localStorage.clear();
            this.setState({loader: false});
            this.props.history.push('/session/login');
          }
        }.bind(this))
        .catch(function(error){
          localStorage.clear();
          this.setState({loader: false});
          this.props.history.push('/session/login');
        });*/
  }

  componentDidMount(){
    if(this.props.location.pathname.indexOf('logout') >= 0){
      this.onUserLogout();
    }else{
      localStorage.clear();
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
                  {/*<div>
                    <Link to="../citizen/form">
                      <Button variant="raised" className="btn-light" onClick={() => this.onUserSignUp()}>Denunciar ao Conselho</Button>
                    </Link>
                    <Link to="../citizen/follow">
                      <Button variant="raised" className="btn-light ml-10" onClick={() => this.onUserFollow()}>Acompanhar Denúncia</Button>
                    </Link>
                  </div>*/}
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
                        <Input type="mail" value={username} name="user-mail" id="user-mail" autoComplete="off" className="has-input input-lg" placeholder="E-mail" onChange={(event) => this.setState({ email: event.target.value })} />
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
