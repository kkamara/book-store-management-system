import React, { useEffect, useState, } from 'react'
import { useDispatch, useSelector, } from 'react-redux'
import { useNavigate, } from "react-router"
import ReactPaginate from 'react-paginate'
import moment from 'moment'
import { Helmet, } from "react-helmet"
import { FontAwesomeIcon, } from "@fortawesome/react-fontawesome"
import { faX, } from "@fortawesome/free-solid-svg-icons"
import { authorize, } from '../../../redux/actions/authActions'
import { getOrders, } from '../../../redux/actions/ordersActions'

import "./OrdersComponent.scss"

export default function HomeComponent() {
  const navigate = useNavigate()
  const dispatch = useDispatch()
  const state = useSelector(state => ({
    auth: state.auth,
    orders: state.orders,
  }))
  const [query, setQuery] = useState("")
  const [page, setPage] = useState(1)

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
      dispatch(getOrders())
    }
  }, [state.auth])

  const handlePageChange = ({ selected, }) => {
    const newPage = selected + 1
    if (newPage > state.orders.data.meta.lastPage) {
      return
    }
    dispatch(getOrders(newPage))
    setPage(newPage)
  }

  const handleQueryChange = e => {
    setQuery(e.target.value)
  }

  const handleSearchFormSubmit = e => {
    e.preventDefault()
    if (0 === query.length) {
      return
    }
    dispatch(getOrders(1, query))
    setPage(1)
  }

  const handleClearSearchInput = () => {
    if (0 === query.length) {
      return
    }
    setQuery("")
    setPage(1)
    dispatch(getOrders(1, ""))
  }

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

  const parseDate = date => moment(date).format('YYYY-MM-DD hh:mm')

  const pagination = () => {
    if (!state.orders.data) {
        return null
    }

    return <div className="orders-pagination">
      <ReactPaginate
        onPageChange={handlePageChange}
        previousLabel="Previous"
        nextLabel="Next"
        pageClassName="page-item"
        pageLinkClassName="page-link"
        previousClassName="page-item"
        previousLinkClassName="page-link"
        nextClassName="page-item"
        nextLinkClassName="page-link"
        breakLabel="..."
        breakClassName="page-item"
        breakLinkClassName="page-link"
        pageCount={state.orders.data.meta.lastPage}
        marginPagesDisplayed={2}
        pageRangeDisplayed={5}
        containerClassName="pagination"
        activeClassName="active"
        forcePage={state.orders.data.meta.currentPage - 1}
      />
    </div>
  }

  const paginationDetail = () => {
    return <>
      <strong>page</strong> ({state.orders.data.meta.currentPage}),
      &nbsp;<strong>page count</strong> ({state.orders.data.meta.lastPage}),
      &nbsp;<strong>displayed items</strong> ({state.orders.data.data.length}),
      &nbsp;<strong>items</strong> ({state.orders.data.meta.total})
    </>
  }

  const renderList = () => {
    if (!state.orders.data) {
      return null
    }
    return (
      <>
        {paginationDetail()}
        <div className="col-md-12">
          {state.orders.data.data.map((order, index) => (
            <div class="card orders-card">
              <div class="card-body">
                <h5 class="card-title">Order {getOrderStatusBadge(order.status)}, Reference: {order.referenceNumber}</h5>
                <h6 class="card-subtitle mb-2 text-body-secondary">Ordered at {parseDate(order.createdAt)}</h6>
                <p class="card-text">
                  Cost: £{order.cost}
                </p>
                <p class="card-text">
                  Delivery cost: £{order.deliveryCost}
                </p>
                <p class="card-text">
                  Total cost: £{order.totalCost}
                </p>
                <div className="order-link-container">
                  <a href={`/orders/${order.referenceNumber}`} class="card-link order-link btn btn-primary">View Order</a>
                </div>
              </div>
            </div>
          ))}
        </div>
        {paginationDetail()}
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
    return <div className="container orders-container text-center">
      <Helmet>
          <title>My Orders | {import.meta.env.VITE_APP_NAME}</title>
      </Helmet>
      <p>Loading...</p>
    </div>
  }

  return (
    <>
      <div className='container orders-container'>
        <Helmet>
            <title>My Orders | {import.meta.env.VITE_APP_NAME}</title>
        </Helmet>
        <div className="col-md-4">
          <form className="search-orders-form" onSubmit={handleSearchFormSubmit}>
            <div className="form-group query-orders-form-group">
              <label htmlFor="query">Search:</label>
              <input
                type="text"
                name="query"
                className="form-control query-input"
                value={query}
                onChange={handleQueryChange}
              />
            </div>
            <div className="form-group search-orders-button-container">
              <div
                className="btn btn-default clear-orders-search-form"
                onClick={handleClearSearchInput}
              >
                <FontAwesomeIcon icon={faX} />
              </div>
              <input
                className="btn btn-primary"
                type="submit"
                value="Submit Search"
              />
            </div>
          </form>
        </div>
        {pagination()}
        {renderList()}
        {pagination()}
      </div>
    </>
  )
}
