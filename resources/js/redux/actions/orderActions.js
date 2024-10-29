
import HttpService from '../../services/HttpService'
import { order, } from '../types'

export const getOrder = referenceNumber => {
  return async dispatch => {
    const http = new HttpService()

    dispatch({ type: order.GET_ORDER_PENDING, })

    const path = '/orders/'+referenceNumber
    await new Promise((resolve, reject) => {
      http.getData(http.domain+'/sanctum/csrf-cookie').then( 
        http.getData(path).then(res => {
          resolve(dispatch({
            type: order.GET_ORDER_SUCCESS,
            payload: res.data,
          }))                
        }, error => {
          reject(dispatch({ 
            type : order.GET_ORDER_ERROR, 
            payload: error,
          }))
        })
      ).catch(error => {
        reject(dispatch({ 
          type : order.GET_ORDER_ERROR, 
          payload: error,
        }))
      })
    })
  }
}
