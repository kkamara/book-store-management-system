import React, { useEffect, useState, } from 'react'
import { useDispatch, useSelector, } from 'react-redux'
import { useParams, useNavigate, } from 'react-router'
import moment from 'moment'
import ReactPaginate from 'react-paginate'
import { Helmet, } from "react-helmet"
import { getBook, } from '../../../redux/actions/bookActions'
import { getReviews, } from '../../../redux/actions/reviewsActions'
import { authorize, } from '../../../redux/actions/authActions'
import { addToCart, } from '../../../redux/actions/cartActions'

import "./BookComponent.scss"

export default function BookComponent() {
  const dispatch = useDispatch()
  const state = useSelector(state => ({
    book: state.book,
    reviews: state.reviews,
    auth: state.auth,
  }))
  let { slug } = useParams()
  const navigate = useNavigate()
  const [showReviews, setShowReviews] = useState("")

  useEffect(() => {
    dispatch(getBook(slug))
    dispatch(authorize())
  }, [])

  useEffect(() => {
    if (
      !state.book.loading &&
      typeof state.book.data === 'object' &&
      null !== state.book.data
    ) {
      dispatch(getReviews(slug))
    }
  }, [state.book])

  useEffect(() => {
    if (state.book.error !== null) {
      return navigate("/notfound")
    }
  }, [state.book])

  const reviewTitle = () => {
    let reviewAverage = null
    if (state.book.data.data.reviewAverage) {
      reviewAverage = state.book.data.data.reviewAverage
    } else {
      return "Reviews"
    }
    return `Reviews (${reviewAverage})`
  }

  const handleAddToCart = () => {
    if (
      !state.auth.loading &&
      null === state.auth.data
    ) {
      alert("Please login or register to add to cart.")
    } else if (
      !state.auth.loading &&
      null !== state.auth.data
    ) {
      dispatch(addToCart(state.book.data.data.id))
    }
  }

  const handlePageChange = ({ selected, }) => {
    const newPage = selected + 1
    if (newPage > state.reviews.data.meta.lastPage) {
      return
    }
    dispatch(getReviews(slug, newPage))
    setShowReviews("show")
  }

  const pagination = () => {
    if (!state.reviews.data) {
        return null
    }

    return <div className="reviews-pagination">
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
        pageCount={state.reviews.data.meta.lastPage}
        marginPagesDisplayed={2}
        pageRangeDisplayed={5}
        containerClassName="pagination"
        activeClassName="active"
        forcePage={state.reviews.data.meta.currentPage - 1}
      />
    </div>
  }

  const paginationDetail = () => {
    return <div className="text-center">
      <strong>page</strong> ({state.reviews.data.meta.currentPage}),
      &nbsp;<strong>page count</strong> ({state.reviews.data.meta.lastPage}),
      &nbsp;<strong>displayed items</strong> ({state.reviews.data.data.length}),
      &nbsp;<strong>items</strong> ({state.reviews.data.meta.total})
    </div>
  }

  const renderReviews = () => {
    if (!state.reviews.data) {
      return null
    }
    return (
      <>
        {paginationDetail()}
        {state.reviews.data.data.map((review, index) => (
          <div key={index} className="card card-body review-card-body">
              <p>Rated <span className="rating">{review.rating}</span></p>
              <p className="review-text">{review.text}</p>
              <p className="float-right">
                Submitted {parseDate(review.createdAt)} by {review.user.name}
              </p>
          </div>
        ))}
        {paginationDetail()}
      </>
    )
  }

  const parseDate = date => moment(date).format('YYYY-MM-DD hh:mm')

  if (
    !state.auth.loading &&
    typeof state.auth.data === 'object' &&
    null !== state.auth.data
  ) {
    console.log('authenticated', state.auth.data)
  }
  if (
    !state.book.loading &&
    typeof state.book.data === 'object' &&
    null !== state.book.data
  ) {
    console.log('book', state.book.data)
  }
  if (state.book.loading || state.reviews.loading) {
    return <div className="container book-container text-center">
      <Helmet>
          <title>{import.meta.env.VITE_APP_NAME}</title>
      </Helmet>
      <p>Loading...</p>
    </div>
  }
  
  return (
    <>
      <div className='container book-container'>
        <Helmet>
            <title>{state.book.data.data.name} | {import.meta.env.VITE_APP_NAME}</title>
        </Helmet>
        <h1>{state.book.data.data.name}</h1>
        <img
          src={state.book.data.data.jpgImageURL}
          alt={state.book.data.data.name}
          className="book-cover"
        />
        <span className="card-span categories-span">
          Categories: 
          {state.book.data.data.categories.map((category, index) => {
            if ((index + 1) === state.book.data.data.categories.length) {
              return category.name
            } else {
              return category.name+", "
            }
          })}
        </span>
        <div className="row">
          <div className="col-md-4 offset-md-4 book-detail">
            <div className="book-detail-info">
              <span className="card-span">Publisher: {state.book.data.data.publisher}</span>
              <span className="card-span">Published {state.book.data.data.published}</span>
              <span className="card-span">Binding: {state.book.data.data.binding}</span>
              <span className="card-span">Edition: {state.book.data.data.edition}</span>
            </div>
            <span className="book-cost">£{state.book.data.data.cost}</span> 
            <button
              className="btn btn-primary add-to-cart"
              onClick={handleAddToCart}
            >
              Add to cart
            </button>
          </div>
        </div>
        <div className="col-md-12 reviews-container">
          <p className="d-inline-flex gap-1">
            <button className="btn btn-info" type="button" data-bs-toggle="collapse" data-bs-target="#descriptionCollapse" aria-expanded="false" aria-controls="descriptionCollapse">
              Description
            </button>
            <button className="btn btn-info" type="button" data-bs-toggle="collapse" data-bs-target="#reviewCollapse" aria-expanded="false" aria-controls="reviewCollapse">
              {reviewTitle()}
            </button>
          </p>
          <div className="collapse description-collapse" id="descriptionCollapse">
            <div className="card card-body book-description">
              {state.book.data.data.description}
            </div>
          </div>
          <div className={`collapse book-collapse ${showReviews}`} id="reviewCollapse">
            {pagination()}
            {renderReviews()}
            {pagination()}
          </div>
        </div>
      </div>
    </>
  )
}
