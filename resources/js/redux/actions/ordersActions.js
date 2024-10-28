
import HttpService from '../../services/HttpService'
import { orders, } from '../types'

export const getOrders = (page=null, query=null) => {
  return async dispatch => {
    const http = new HttpService()
        
    dispatch({ type: orders.GET_ORDERS_PENDING, })

    const tokenId = "user-token"
    const searchParams = new URLSearchParams()
    let path = "/orders"
    if (page) {
      searchParams.append("page", page)
    }
    if (query) {
      searchParams.append("query", query)
    }
    const urlParams = searchParams.toString()
    if (urlParams.length) {
      path = `${path}?${urlParams}`
    }
    await new Promise((resolve, reject) => {
      http.getData(http.domain+'/sanctum/csrf-cookie').then( 
        http.getData(path, tokenId).then(res => {
          resolve(dispatch({
            type: orders.GET_ORDERS_SUCCESS,
            payload: res.data,
          }))                
        }, error => {
          reject(dispatch({ 
            type : orders.GET_ORDERS_ERROR, 
            payload: error,
          }))
        })
      ).catch(error => {
        reject(dispatch({ 
          type : orders.GET_ORDERS_ERROR, 
          payload: error,
        }))
      })
    })
  }
}
