import React, { Component } from 'react';
import logo from '../logo.svg';
import MainMenu from './menu/main-menu';

class MainHeader extends Component {
  render() {
    return (
      <header className="main-header">
        <div className="container">
          <div className="row">
            <div className="col-xs-4 col-md-2">
              <img src={logo} className="main-header__logo" alt="logo" />
            </div>
            <div className="col-xs-4 col-md-4">
              <h1 className="main-header__title">My page</h1>
            </div>
            <div className="col-xs-4 col-md-6">
              <MainMenu></MainMenu>
            </div>
          </div>
        </div>
      </header>
    );
  }
}

export default MainHeader;