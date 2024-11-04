import React, { useEffect, useState, } from 'react'
import { useDispatch, useSelector, } from 'react-redux'
import { Link, } from 'react-router-dom'
import { authorize, } from "../../redux/actions/authActions"
import { getCart, } from "../../redux/actions/cartActions"

import "./Header.scss"

export default function Header(props) {  
  const state = useSelector(state => ({
    auth: state.auth,
    cart: state.cart,
  }))
  const dispatch = useDispatch()
  const [cartCount, setCartCount] = useState(0)

  useEffect(() => {
    if (
      !state.cart.loading &&
      typeof state.cart.data === "object" &&
      null !== state.cart.data
    ) {
      const quantity = state.cart.data.data.reduce((acc, curr) => acc + curr.quantity, 0)
      setCartCount(quantity)
    }
  })

  useEffect(() => {
    dispatch(getCart())
  }, state.cart)

  useEffect(() => {
    dispatch(authorize())
  }, state.auth)

  const renderNavLinks = () => {
    if(state.auth.data) {
      return <>
        <li className="nav-item dropdown">
          <a className="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            User
          </a>
          <ul className="dropdown-menu">
            <li>
              <Link
                className="dropdown-item"
                to="/user/account"
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
            Cart ({cartCount})
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
  
  if (
    !state.cart.loading &&
    typeof state.cart.data === "object" &&
    null !== state.cart.data
  ) {
    console.log("cart", state.cart.data)
  }
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
            <Link className="nav-link active" aria-current="page" to="/books/search">Search Books</Link>
          </li>
        </ul>
        <ul className="navbar-nav">
          {renderNavLinks()}
        </ul>
      </div>
    </div>
  </nav>
}
