import React, { useEffect, } from 'react'
import { useDispatch, useSelector, } from 'react-redux'
import { useNavigate, } from "react-router"
import { Helmet, } from "react-helmet"
import { FontAwesomeIcon, } from "@fortawesome/react-fontawesome"
import { faMinus, faPlus, } from "@fortawesome/free-solid-svg-icons"
import { authorize, } from '../../../redux/actions/authActions'
import {
  getCart,
  addToCart,
  removeFromCart,
} from '../../../redux/actions/cartActions'

import "./CartComponent.scss"

export default function CartComponent() {
  const navigate = useNavigate()
  const dispatch = useDispatch()
  const state = useSelector(state => ({
    auth: state.auth,
    cart: state.cart,
  }))

  useEffect(() => {
    dispatch(authorize())
  }, [])

  useEffect(() => {
    if (
      !state.auth.loading &&
      null === state.auth.data
    ) {
      navigate("/user/login")
    } else {
      dispatch(getCart())
    }
  }, [state.auth])

  const handleAddToCart = bookId => {
    dispatch(addToCart(bookId))
  }

  const handleRemoveFromCart = bookId => {
    dispatch(removeFromCart(bookId))
  }

  const getCost = () => {
    let cost = 3.99
    state.cart.data.data.forEach(cartItem => {
      cost += parseFloat(cartItem.cost)
    })
    return (Math.trunc(cost * 100) / 100).toFixed(2)
  }

  const renderList = () => {
    if (!state.cart.data) {
      return null
    }
    
    return (
      <>
        <div className="col-md-12">
          {state.cart.data.data.map((cartItem, index) => (
            <div class="card cart-card">
              <div class="card-body">
                <h5 class="card-title">{cartItem.book.name}</h5>
                <h6 class="card-subtitle mb-2 text-body-secondary"></h6>
                <p class="card-text">
                  Cost: £{cartItem.cost}
                </p>
                <div className="cart-icons-container">
                  <div
                    className="btn btn-default"
                    onClick={() => { handleRemoveFromCart(cartItem.book.id) }}
                  >
                    <FontAwesomeIcon icon={faMinus} />
                  </div>
                  <div className="btn btn-default">
                    {cartItem.quantity}
                  </div>
                  <div
                    className="btn btn-default"
                    onClick={() => { handleAddToCart(cartItem.book.id) }}
                  >
                    <FontAwesomeIcon icon={faPlus} />
                  </div>
                </div>
                <div className="cart-link-container">
                  <a
                    href={`/books/${cartItem.book.slug}`}
                    class="card-link order-link btn btn-primary"
                  >
                    View Book
                  </a>
                </div>
              </div>
            </div>
          ))}
        </div>
      </>
    )
  }

  if (
    !state.auth.loading &&
    typeof state.auth.data === 'object' &&
    null !== state.auth.data
  ) {
    console.log('auth', state.auth.data)
  }
  if (state.auth.loading) {
    return <div className="container cart-container text-center">
      <Helmet>
          <title>Cart | {import.meta.env.VITE_APP_NAME}</title>
      </Helmet>
      <p>Loading...</p>
    </div>
  }

  return (
    <>
      <div className='container cart-container'>
        <Helmet>
            <title>Cart | {import.meta.env.VITE_APP_NAME}</title>
        </Helmet>
        <div className="header-container">
          <h1>Cart</h1>
        </div>
        <div className="row">
          <div className="col-md-9">
            {renderList()}
          </div>
          <div className="col-md-3">
            <div className="checkout-detail">
              <div>
                Delivery cost: £3.99
              </div>
              <div>
                Total cost: £{getCost()}
              </div>
            </div>
            <div className="cart-checkout-icons-container">
              <a
                className="btn btn-success"
              >
                Checkout
              </a>
            </div>
          </div>
        </div>
      </div>
    </>
  )
}
