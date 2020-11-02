import React, { Component } from "react";
import ReactDOM from "react-dom";

export default class Example extends Component {

  constructor(props){
    super(props);

  }

  

  render() {
   
    return <h1 className="text-center text-success">...</h1>;
  }
}

if (document.getElementById("app")) {
  ReactDOM.render(<Example />, document.getElementById("app"));
}
