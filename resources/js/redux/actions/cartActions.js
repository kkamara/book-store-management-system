
import HttpService from '../../services/HttpService'
import { cart, } from '../types'

export const getCart = () => {
  return async dispatch => {
    const http = new HttpService()
    
    dispatch({ type: cart.GET_CART_PENDING, })

    const tokenId = "user-token"
    const path = "/cart"
    await new Promise((resolve, reject) => {
      http.getData(http.domain+'/sanctum/csrf-cookie').then( 
        http.getData(path, tokenId).then(res => {
          resolve(dispatch({
            type: cart.GET_CART_SUCCESS,
            payload: res.data,
          }))                
        }, error => {
          reject(dispatch({ 
            type : cart.GET_CART_ERROR, 
            payload: error,
          }))
        })
      ).catch(error => {
        reject(dispatch({ 
          type : cart.GET_CART_ERROR, 
          payload: error,
        }))
      })
    })
  }
}
