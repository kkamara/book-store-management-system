import React, { useEffect, useState, } from 'react'
import { useDispatch, useSelector, } from 'react-redux'
import { useParams, useNavigate, } from 'react-router'
import moment from 'moment'
import ReactPaginate from 'react-paginate'
import { Helmet, } from "react-helmet"
import { getBook, } from '../../../redux/actions/bookActions'
import { getReviews, } from '../../../redux/actions/reviewsActions'

import "./BookComponent.scss"

export default function BookComponent() {
  const dispatch = useDispatch()
  const state = useSelector(state => ({
    book: state.book,
    reviews: state.reviews,
  }))
  let { slug } = useParams()
  const navigate = useNavigate()
  const [showReviews, setShowReviews] = useState("")

  useEffect(() => {
    dispatch(getBook(slug))
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

  const handlePageChange = ({ selected, }) => {
    const newPage = selected + 1
    if (selected > state.reviews.data.last_page) {
      return
    }
    dispatch(getReviews(slug, newPage))
    setShowReviews("show")
  }

  const pagination = () => {
    if (!state.reviews.data) {
        return null
    }

    return <div className="review-pagination">
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
        pageCount={state.reviews.data.meta.last_page}
        marginPagesDisplayed={2}
        pageRangeDisplayed={5}
        containerClassName="pagination"
        activeClassName="active"
        forcePage={state.reviews.data.meta.current_page - 1}
      />
    </div>
  }

  const paginationDetail = () => {
    return <div className="text-center">
      <strong>page</strong> ({state.reviews.data.meta.current_page}),
      &nbsp;<strong>page count</strong> ({state.reviews.data.meta.last_page}),
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
        <div className="col-md-12 book-detail">
          <span className="book-cost">£{state.book.data.data.cost}</span> <button className="btn btn-primary add-to-cart">Add to cart</button>
        </div>
        <div className="col-md-12 review-container">
          <p className="d-inline-flex gap-1">
            <button className="btn btn-info" type="button" data-bs-toggle="collapse" data-bs-target="#descriptionCollapse" aria-expanded="false" aria-controls="descriptionCollapse">
              Description
            </button>
            <button className="btn btn-info" type="button" data-bs-toggle="collapse" data-bs-target="#reviewCollapse" aria-expanded="false" aria-controls="reviewCollapse">
              {reviewTitle()}
            </button>
          </p>
          <div className="collapse description-collapse" id="descriptionCollapse">
            <div className="card card-body">
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
