import React, { useEffect, } from 'react'
import { useDispatch, useSelector, } from 'react-redux'
import ReactPaginate from 'react-paginate'
import moment from 'moment'
import { Helmet, } from "react-helmet"
import { getHome, } from '../../redux/actions/homeActions'

import "./HomeComponent.scss"

export default function HomeComponent() {
  const dispatch = useDispatch()
  const state = useSelector(state => ({
    home: state.home,
  }))

  useEffect(() => {
    dispatch(getHome())
  }, [])

  const handlePageChange = ({ selected, }) => {
    const newPage = selected + 1
    if (newPage > state.home.data.meta.lastPage) {
      return
    }
    dispatch(getHome(newPage))
  }

  const parseDate = date => moment(date).format('YYYY-MM-DD hh:mm')

  const pagination = () => {
    if (!state.home.data) {
        return null
    }

    return <div className="book-pagination">
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
        pageCount={state.home.data.meta.lastPage}
        marginPagesDisplayed={2}
        pageRangeDisplayed={5}
        containerClassName="pagination"
        activeClassName="active"
        forcePage={state.home.data.meta.currentPage - 1}
      />
    </div>
  }

  const paginationDetail = () => {
    return <>
      <strong>page</strong> ({state.home.data.meta.currentPage}),
      &nbsp;<strong>page count</strong> ({state.home.data.meta.lastPage}),
      &nbsp;<strong>displayed items</strong> ({state.home.data.data.length}),
      &nbsp;<strong>items</strong> ({state.home.data.meta.total})
    </>
  }

  const renderList = () => {
    if (!state.home.data) {
      return null
    }
    return (
      <>
        {paginationDetail()}
        <div className="col-md-12">
          {state.home.data.data.map((book, index) => (
            <div key={index} className="card home-card">
              <a href={`/books/${book.slug}`}>
                <img src={book.jpgImageURL} className="card-img-top" alt="..." />
              </a>
              <div className="card-body">
                <h5 className="card-title book-card-title">{book.name}</h5>
                <p className="card-text">
                  <span className="card-span">Publisher: {book.publisher}</span>
                  <span className="card-span">Published {book.published}</span>
                  <span className="card-span book-cost">£{book.cost}</span>
                  <span className="card-span">Binding: {book.binding}</span>
                  <span className="card-span">Edition: {book.edition}</span>
                  <span className="card-span categories-span">
                    Categories: 
                    {book.categories.map((category, index) => {
                      if ((index + 1) === book.categories.length) {
                        return category.name
                      } else {
                        return category.name+", "
                      }
                    })}
                  </span>
                </p>
                <a href={`/books/${book.slug}`} className="btn btn-primary">
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
    !state.home.loading &&
    typeof state.home.data === 'object' &&
    null !== state.home.data
  ) {
    console.log('home', state.home.data)
  }
  if (state.home.loading) {
    return <div className="container home-container text-center">
      <Helmet>
          <title>Home | {import.meta.env.VITE_APP_NAME}</title>
      </Helmet>
      <p>Loading...</p>
    </div>
  }

  return (
    <>
      <div className='container home-container'>
        <Helmet>
            <title>Home | {import.meta.env.VITE_APP_NAME}</title>
        </Helmet>
        {pagination()}
        {renderList()}
        {pagination()}
      </div>
    </>
  )
}
