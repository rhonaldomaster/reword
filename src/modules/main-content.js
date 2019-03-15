import React, { Component } from 'react';
// import { BrowserRouter as Router, Route } from 'react-router-dom';
import axios from 'axios';

class MainContent extends Component {
  constructor(props) {
    super(props);
    this.state = { data: '' };
  }

  componentDidMount() {
    this.loadSiteInfo();
  }

  loadSiteInfo() {
    axios
      .get('https://vertexconnector.dev/wp-json/api/v1/get-site-info')
      .then(({ data }) => {
        this.setState(state => ({data: data}))
      });
  }

  render() {
    return (
      <div>
        {JSON.stringify(this.state.data)}
      </div>
    );
  }
}

export default MainContent;