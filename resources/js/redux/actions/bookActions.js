
import HttpService from '../../services/HttpService'
import { book, } from '../types'

export const getBook = id => {
  return async dispatch => {
    const http = new HttpService()

    dispatch({ type: book.GET_BOOK_PENDING, })

    const path = '/books/'+id
    await new Promise((resolve, reject) => {
      http.getData(http.domain+'/sanctum/csrf-cookie').then( 
        http.getData(path).then(res => {
          resolve(dispatch({
            type: book.GET_BOOK_SUCCESS,
            payload: res.data,
          }))                
        }, error => {
          reject(dispatch({ 
            type : book.GET_BOOK_ERROR, 
            payload: error,
          }))
        })
      ).catch(error => {
        reject(dispatch({ 
          type : book.GET_BOOK_ERROR, 
          payload: error,
        }))
      })
    })
  }
}
