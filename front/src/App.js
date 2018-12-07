import React, { Component } from 'react';
import { Provider } from "react-redux";
import { BrowserRouter as Router, Route } from "react-router-dom";
import 'bootstrap/dist/css/bootstrap.min.css';
import './styles/main.css';

import store from "./store/store";
import StartPage from './components/StartPage';

class App extends Component {
  render() {
    return (
        <Provider store={store}>
            <Router>
                <div>
                    <Route exact path="/start" component={StartPage} />
                </div>
            </Router>
        </Provider>
    );
  }
}

export default App;
