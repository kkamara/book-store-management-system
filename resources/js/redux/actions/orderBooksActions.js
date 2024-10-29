
import HttpService from '../../services/HttpService'
import { orderBooks, } from '../types'

export const getOrderBooks = referenceNumber => {
  return async dispatch => {
    const http = new HttpService()

    dispatch({ type: orderBooks.GET_ORDER_BOOKS_PENDING, })

    const path = "/orders/"+referenceNumber+"/products"
    await new Promise((resolve, reject) => {
      http.getData(http.domain+'/sanctum/csrf-cookie').then( 
        http.getData(path).then(res => {
          resolve(dispatch({
            type: orderBooks.GET_ORDER_BOOKS_SUCCESS,
            payload: res.data,
          }))                
        }, error => {
          reject(dispatch({ 
            type : orderBooks.GET_ORDER_BOOKS_ERROR, 
            payload: error,
          }))
        })
      ).catch(error => {
        reject(dispatch({ 
          type : orderBooks.GET_ORDER_BOOKS_ERROR, 
          payload: error,
        }))
      })
    })
  }
}
