import React, { Component } from 'react';

class MainMenu extends Component {
  render() {
    return (
      <ul className="main-header__menu">
        <li>
          <a href="/">Home</a>
        </li>
        <li>
          <a href="/catalog">Catalog</a>
        </li>
      </ul>
    );
  }
}

export default MainMenu;