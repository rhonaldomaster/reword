import React, { Component } from 'react';

class MainFooter extends Component {
  render() {
    return (
      <footer className="main-footer">
        <div className="container">
          <div className="row">
            <div className="col-xs-12 col-md-3">
              <h1 className="main-footer__title">Footer</h1>
            </div>
            <div className="col-xs-12 col-md-9">
              <ul className="main-footer__menu">
                <li>Home</li>
                <li>About</li>
                <li>Terms and conditions</li>
              </ul>
            </div>
          </div>
        </div>
      </footer>
    );
  }
}

export default MainFooter;