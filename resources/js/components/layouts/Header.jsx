import React, { useEffect, } from 'react'
import { useDispatch, useSelector, } from 'react-redux'
import { Link, } from 'react-router-dom'
import { authorize, } from "../../redux/actions/authActions"

import "./Header.scss"

export default function Header(props) {  
  const authResponse = useSelector(state=>state.auth)
  const dispatch = useDispatch()

  useEffect(() => {
    dispatch(authorize())
  }, [authResponse.auth])

  const renderNavLinks = () => {
    if(authResponse.data) {
      return <>
        <li className="nav-item dropdown">
          <a className="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            User
          </a>
          <ul className="dropdown-menu">
            <li>
              <Link
                className="dropdown-item"
                to="/"
              >
                Account
              </Link>
            </li>
            <li>
              <Link
                className="dropdown-item"
                to="/orders"
              >
                My Orders
              </Link>
            </li>
            <li>
              <Link
                className="dropdown-item" 
                to="/user/logout"
              >
                Logout
              </Link>
            </li>
          </ul>
        </li>
        <li className="nav-item">
          <Link
            className="nav-link active" 
            aria-current="page" 
            to="/"
          >
            Cart (0)
          </Link>
        </li>
      </>
    } else {
      return <>
        <li className="nav-item">
          <Link
            className="nav-link active" 
            aria-current="page" 
            to="/user/login"
          >
            Login
          </Link>
        </li>
        <li className="nav-item">
          <Link
            className="nav-link active" 
            aria-current="page" 
            to="/user/register"
          >
            Register
          </Link>
        </li>
        <li className="nav-item">
          <Link
            className="nav-link active" 
            aria-current="page" 
            to="/"
          >
            Cart (0)
          </Link>
        </li>
      </>
    }
  }
  // VITE_TEST
  console.log(import.meta.env)
  return <nav className="container navbar navbar-expand-lg bg-body-tertiary">
    <div className="container-fluid">
      <Link className="navbar-brand" to="/">Book Store Management System</Link>
      <button className="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span className="navbar-toggler-icon"></span>
      </button>
      <div className="collapse navbar-collapse" id="navbarSupportedContent">
        <ul className="navbar-nav me-auto mb-2 mb-lg-0">
          <li className="nav-item">
            <Link className="nav-link active" aria-current="page" to="/">Home</Link>
          </li>
          <li className="nav-item">
            <Link className="nav-link active" aria-current="page" to="/">Search Books</Link>
          </li>
        </ul>
        <ul className="navbar-nav">
          {renderNavLinks()}
        </ul>
      </div>
    </div>
  </nav>
}
