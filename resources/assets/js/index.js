import React,{Component} from 'react';
import ReactDOM from "react-dom";
import Header from "./components/header";
import Products from "./components/products";
import Home from "./components/home";
import Test from "./components/Apitest";
import {
    BrowserRouter as Router,
    Switch,
    Route,
    
  } from "react-router-dom";

class App extends Component {

    render() { 
        return ( 
            <Router>
                <div>
               <Header></Header>
               
               <Switch>
          <Route path="/products" exact>
            <Products></Products>
          </Route>
          <Route path="/test" exact>
            <Test></Test>
          </Route>
          <Route path="/" exact>
            <Home />
          </Route>
        </Switch>
           </div>
            </Router>
           
         );
    }
}
 
export default App;

if (document.getElementById("app")) {
    ReactDOM.render(<App />, document.getElementById("app"));
  }

