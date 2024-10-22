import React, { useEffect, } from 'react'
import { useDispatch, useSelector, } from 'react-redux'
import ReactPaginate from 'react-paginate'
import moment from 'moment'
import { getHome, } from '../../redux/actions/homeActions'

import "./HomeComponent.scss"

export default function HomeComponent() {
  const dispatch = useDispatch()
  const state = useSelector(state => ({
    auth: state.auth,
    home: state.home,
  }))

  useEffect(() => {
    dispatch(getHome())
  }, [])

  const handlePageChange = ({ selected, }) => {
    const newPage = selected + 1
    if (selected > state.home.data.last_page) {
      return
    }
    dispatch(getHome(newPage))
  }

  const parseDate = date => moment(date).format('YYYY-MM-DD hh:mm')

  const pagination = () => {
    if (!state.home.data) {
        return null
    }

    return <ReactPaginate
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
      pageCount={state.home.data.meta.last_page}
      marginPagesDisplayed={2}
      pageRangeDisplayed={5}
      containerClassName="pagination"
      activeClassName="active"
      forcePage={state.home.data.meta.current_page - 1}
    />
  }

  const paginationDetail = () => {
    return <>
      <strong>page</strong> ({state.home.data.meta.current_page}),
      <strong>page_count</strong> ({state.home.data.meta.last_page}),
      <strong>displayed_items</strong> ({state.home.data.data.length}),
      <strong>items</strong> ({state.home.data.meta.total})
    </>
  }

  const renderList = () => {
    if (!state.home.data) {
      return null
    }
    return (
      <>
        <div className="col-md-12">
          {state.home.data.data.map((book, index) => (
            <div key={index} className="card home-card">
              <a href="#">
                <img src={book.jpgImageURL} className="card-img-top" alt="..." />
              </a>
              <div className="card-body">
                <h5 className="card-title">{book.name}</h5>
                <p className="card-text">
                  <span className="card-span">Publisher: {book.publisher}</span>
                  <span className="card-span">Published {book.published}</span>
                  <span className="card-span book-cost">£{book.cost}</span>
                </p>
                <a href="#" className="btn btn-primary">
                  View Book
                </a>
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
    console.log('authenticated', state.auth.data)
  }
  if (
    !state.home.loading &&
    typeof state.home.data === 'object' &&
    null !== state.home.data
  ) {
    console.log('home', state.home.data)
  }
  if (state.auth.loading || state.home.loading) {
    return <div className="container home-container text-center">
      <p>Loading...</p>
    </div>
  }

  return (
    <>
      <div className='container home-container'>
        {pagination()}
        {renderList()}
        {pagination()}
      </div>
    </>
  )
}
