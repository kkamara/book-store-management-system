import React, { useEffect, } from 'react'
import { useDispatch, useSelector, } from 'react-redux'
import { useParams, useNavigate, } from 'react-router'
import moment from 'moment'
import { Helmet, } from "react-helmet"
import { getOrder, } from '../../../redux/actions/orderActions'
import { getOrderBooks, } from '../../../redux/actions/orderBooksActions'
import { authorize, } from '../../../redux/actions/authActions'

import "./OrderComponent.scss"

export default function OrderComponent() {
  const dispatch = useDispatch()
  const state = useSelector(state => ({
    auth: state.auth,
    order: state.order,
    orderBooks: state.orderBooks,
  }))
  let { referenceNumber, } = useParams()
  const navigate = useNavigate()

  useEffect(() => {
    dispatch(getOrder(referenceNumber))
    dispatch(authorize())
  }, [])

  useEffect(() => {
    if (
      !state.order.loading &&
      typeof state.order.data === 'object' &&
      null !== state.order.data
    ) {
      dispatch(getOrderBooks(referenceNumber))
    }
  }, [state.order])

  useEffect(() => {
    if (state.order.error !== null) {
      return navigate("/notfound")
    }
  }, [state.order])

  useEffect(() => {
    if (
      !state.auth.loading &&
      null === state.auth.data
    ) {
      navigate("/user/login")
    }
  }, [state.auth])

  const parseDate = date => moment(date).format('YYYY-MM-DD hh:mm')

  const getOrderStatusBadge = status => {
    let result = null
    switch(status) {
      case "PROCESSING":
        result = <div className="btn btn-info">
          {status}
        </div>
        break
      case "PROCESSED":
        result = <div className="btn btn-info">
          {status}
        </div>
        break
      case "DELIVERING":
        result = <div className="btn btn-info">
          {status}
        </div>
        break
      case "DELIVERED":
        result = <div className="btn btn-success">
          {status}
        </div>
        break
      default:
        result = <div className="btn btn-warning">
          {status}
        </div>
        break
    }
    return result
  }

  if (
    !state.auth.loading &&
    typeof state.auth.data === 'object' &&
    null !== state.auth.data
  ) {
    console.log('authenticated', state.auth.data)
  }
  if (
    !state.order.loading &&
    typeof state.order.data === 'object' &&
    null !== state.order.data
  ) {
    console.log('order', state.order.data)
  }
  if (
    !state.orderBooks.loading &&
    typeof state.orderBooks.data === 'object' &&
    null !== state.orderBooks.data
  ) {
    console.log('orderBooks', state.orderBooks.data)
  }
  if (state.order.loading || state.orderBooks.loading) {
    return <div className="container order-container text-center">
      <Helmet>
          <title>My Order | {import.meta.env.VITE_APP_NAME}</title>
      </Helmet>
      <p>Loading...</p>
    </div>
  }
  
  return (
    <>
      <div className='container order-container'>
        <Helmet>
            <title>Order {state.order.data.data.referenceNumber} | {import.meta.env.VITE_APP_NAME}</title>
        </Helmet>
        <h1>Order {getOrderStatusBadge(state.order.data.data.status)}, Reference: {state.order.data.data.referenceNumber}</h1>
        <div className="col-md-12 product-detail">
          <p className="card-text">
            Ordered at {parseDate(state.order.data.data.createdAt)}
          </p>
          <p className="card-text">
            Cost: £{state.order.data.data.cost}
          </p>
          <p className="card-text">
            Delivery cost: £{state.order.data.data.deliveryCost}
          </p>
          <p className="card-text">
            Total cost: £{state.order.data.data.totalCost}
          </p>
        </div>
        <div className="col-md-12">
          {state.orderBooks.data.data.map((orderBook, index) => (
            <div key={index} className="card order-card">
              <div className="card-header">
                <img 
                  className="order-book-image"
                  src={orderBook.jpgImageURL} 
                  alt={orderBook.name}
                />
              </div>
              <div className="card-body">
                <div className="card-body-inner-container">
                  <h5 className="card-title">{orderBook.name}</h5>
                  <h6 className="card-subtitle mb-2 text-body-secondary">Publisher: {orderBook.publisher}</h6>
                  <p className="card-text">
                    Cost: £{orderBook.cost}
                  </p>
                </div>
                <div className="product-link-container">
                  <a
                    href={`/books/${orderBook.slug}`}
                    className="card-link product-link btn btn-primary"
                  >
                    View Product
                  </a>
                </div>
              </div>
            </div>
          ))}
        </div>
      </div>
    </>
  )
}
