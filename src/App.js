import React, { Component } from 'react';
import MainHeader from './modules/main-header';
import MainFooter from './modules/main-footer';
import MainContent from './modules/main-content';
import './App.css';

class App extends Component {
  render() {
    return (
      <main>
        <MainHeader></MainHeader>
        <MainContent></MainContent>
        <MainFooter></MainFooter>
      </main>
    );
  }
}

export default App;
