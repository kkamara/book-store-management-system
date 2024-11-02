import React, { useEffect, useState, } from 'react'
import { useDispatch, useSelector, } from 'react-redux'
import ReactPaginate from 'react-paginate'
import moment from 'moment'
import { Helmet, } from "react-helmet"
import { FontAwesomeIcon, } from "@fortawesome/react-fontawesome"
import { faX, } from "@fortawesome/free-solid-svg-icons"
import { getEditions, } from '../../../redux/actions/editionsActions'
import { getCategories, } from '../../../redux/actions/categoriesActions'
import { getHome, } from '../../../redux/actions/homeActions'

import "./SearchBooksComponent.scss"

export default function SearchBooksComponent() {
  const dispatch = useDispatch()
  const state = useSelector(state => ({
    home: state.home,
    editions: state.editions,
    categories: state.categories,
  }))
  const [query, setQuery] = useState("")
  const [page, setPage] = useState(1)
  const [editions, setEditions] = useState(null)
  const [categories, setCategories] = useState(null)
  const [edition, setEdition] = useState(null)
  const [category, setCategory] = useState(null)
  const [orderById, setOrderById] = useState("desc")

  useEffect(() => {
    dispatch(getHome())
    dispatch(getEditions())
    dispatch(getCategories())
  }, [])

  useEffect(() => {
    if (
      !state.editions.loading &&
      typeof state.editions.data === 'object' &&
      null !== state.editions.data
    ) {
      setEditions(state.editions.data.data)
    }
  }, [state.editions])

  useEffect(() => {
    if (
      !state.categories.loading &&
      typeof state.categories.data === 'object' &&
      null !== state.categories.data
    ) {
      setCategories(state.categories.data.data)
    }
  }, [state.categories])

  const handlePageChange = ({ selected, }) => {
    const newPage = selected + 1
    if (newPage > state.home.data.meta.lastPage) {
      return
    }
    dispatch(getHome(newPage))
  }

  const handleClearSearchInput = () => {
    if (0 === query.length) {
      return
    }
    setQuery("")
    setPage(1)
    dispatch(getOrders(1, ""))
  }

  const handleSearchFormSubmit = e => {
    e.preventDefault()
    if (0 === query.length) {
      return
    }
    dispatch(getOrders(1, query))
    setPage(1)
  }

  const handleQueryChange = e => {
    setQuery(e.target.value)
  }

  const handleEditionChange = e => {
    setEdition(e.target.value)
  }

  const handleCategoryChange = e => {
    setCategory(e.target.value)
  }

  const handleOrderByIdChange = e => {
    setOrderById(e.target.value)
  }

  const parseDate = date => moment(date).format('YYYY-MM-DD hh:mm')

  const pagination = () => {
    if (!state.home.data) {
        return null
    }

    return <div className="search-books-pagination">
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
            <div key={index} className="card search-books-card">
              <a href={`/books/${book.slug}`}>
                <img src={book.jpgImageURL} className="card-img-top" alt="..." />
              </a>
              <div className="card-body">
                <h5 className="card-title search-book-card-title">{book.name}</h5>
                <p className="card-text">
                  <span className="card-span">Publisher: {book.publisher}</span>
                  <span className="card-span">Published {book.published}</span>
                  <span className="card-span book-cost">£{book.cost}</span>
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
  if (
    !state.editions.loading &&
    typeof state.editions.data === 'object' &&
    null !== state.editions.data
  ) {
    console.log('editions', state.editions.data)
  }
  if (
    !state.categories.loading &&
    typeof state.categories.data === 'object' &&
    null !== state.categories.data
  ) {
    console.log('categories', state.categories.data)
  }
  if (
    state.home.loading ||
    state.editions.loading ||
    state.categories.loading
  ) {
    return <div className="container search-books-container text-center">
      <Helmet>
          <title>Search Books | {import.meta.env.VITE_APP_NAME}</title>
      </Helmet>
      <p>Loading...</p>
    </div>
  }

  return (
    <>
      <div className='container search-books-container'>
        <Helmet>
            <title>Search Books | {import.meta.env.VITE_APP_NAME}</title>
        </Helmet>
        <form className="row search-books-form" onSubmit={handleSearchFormSubmit}>
          <div className="col-md-4">
            <div className="form-group query-search-books-form-group">
              <label htmlFor="query">Search:</label>
              <input
                type="text"
                name="query"
                className="form-control query-input"
                value={query}
                onChange={handleQueryChange}
              />
            </div>            
          </div>
          <div className="col-md-4">
            <div className="form-group">
              <label htmlFor="edition">Select edition:</label>
              <select
                name="edition"
                id="edition"
                className="form-control"
                value={edition}
                onChange={handleEditionChange}
              >
                {editions && editions.map((edition, index) => (
                  <option
                    key={index}
                    value={edition.filterKey}
                  >
                    {edition.name}
                  </option>
                ))}
              </select>
            </div>          
          </div>
          <div className="col-md-4 category-grid">
            <div className="form-group">
              <label htmlFor="category">Select category:</label>
              <select
                name="category"
                id="category"
                className="form-control"
                value={category}
                onChange={handleCategoryChange}
              >
                {categories && categories.map((category, index) => (
                  <option
                    key={index}
                    value={category.name}
                  >
                    {category.name}
                  </option>
                ))}
              </select>
            </div>
            <div className="form-group">
              <label htmlFor="orderById">Order by:</label>
              <select
                name="orderById"
                id="orderById"
                className="form-control"
                value={orderById}
                onChange={handleOrderByIdChange}
              >
                <option value="desc">ID descending</option>
                <option value="asc">ID ascending</option>
              </select>
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
          </div>
        </form>
        <br />
        {pagination()}
        {renderList()}
        {pagination()}
      </div>
    </>
  )
}
