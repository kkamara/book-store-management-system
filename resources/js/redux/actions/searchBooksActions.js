
import HttpService from '../../services/HttpService'
import { searchBooks, } from '../types'

export const getSearchBooks = (
  page=null,
  params=null,
) => {
  return async dispatch => {
    const http = new HttpService()
    
    dispatch({ type: searchBooks.GET_SEARCH_BOOKS_PENDING, })

    const tokenId = "user-token"
    let path = "/books/search"
    const queryParams = new URLSearchParams()
    if (page) {
      queryParams.append("page", page)
    }
    if (params) {
      if (params.edition) {
        queryParams.append(params.edition, 1)
      }
      if (params.category) {
        queryParams.append("category", params.category)
      }
      if (params.query) {
        queryParams.append("query", params.edition)
      }
      if (params.orderById) {
        queryParams.append("orderById", params.orderById)
      }
    }
    
    if (queryParams.toString()) {
      path = path+"?"+queryParams.toString()
    }
    await new Promise((resolve, reject) => {
      http.getData(http.domain+'/sanctum/csrf-cookie').then( 
        http.getData(path, tokenId).then(res => {
          resolve(dispatch({
            type: searchBooks.GET_SEARCH_BOOKS_SUCCESS,
            payload: res.data,
          }))                
        }, error => {
          reject(dispatch({ 
            type : searchBooks.GET_SEARCH_BOOKS_ERROR, 
            payload: error,
          }))
        })
      ).catch(error => {
        reject(dispatch({ 
          type : searchBooks.GET_SEARCH_BOOKS_ERROR, 
          payload: error,
        }))
      })
    })
  }
}
