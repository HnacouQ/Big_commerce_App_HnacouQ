import React from "react";
import { Link } from 'react-router-dom';

function Header() {
  return (
    <nav className="navbar navbar-expand-lg navbar-dark bg-dark">
      <a className="navbar-brand" href="#">
        Quốc Anh
      </a>
      <button
        className="navbar-toggler"
        type="button"
        data-toggle="collapse"
        data-target="#navbarSupportedContent"
        aria-controls="navbarSupportedContent"
        aria-expanded="false"
        aria-label="Toggle navigation"
      >
        <span className="navbar-toggler-icon" />
      </button>
      <div className="collapse navbar-collapse" id="navbarSupportedContent">
        <ul className="navbar-nav mr-auto">
          <li className="nav-item active">
            <span className="nav-link">
              <Link to="/">Home</Link> <span className="sr-only">(current)</span>
            </span>
          </li>
          <li className="nav-item">
            <span className="nav-link">
              <Link to="/products">Product</Link>
            </span>
          </li>
        </ul>
      </div>
    </nav>
  );
}

export default Header;
