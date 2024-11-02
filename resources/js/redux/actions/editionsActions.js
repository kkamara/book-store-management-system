
import HttpService from '../../services/HttpService'
import { editions, } from '../types'

export const getEditions = () => {
  return async dispatch => {
    const http = new HttpService()

    dispatch({ type: editions.GET_EDITIONS_PENDING, })

    const path = "/books/search/editions"
    await new Promise((resolve, reject) => {
      http.getData(http.domain+'/sanctum/csrf-cookie').then( 
        http.getData(path).then(res => {
          resolve(dispatch({
            type: editions.GET_EDITIONS_SUCCESS,
            payload: res.data,
          }))                
        }, error => {
          reject(dispatch({ 
            type : editions.GET_EDITIONS_ERROR, 
            payload: error,
          }))
        })
      ).catch(error => {
        reject(dispatch({ 
          type : editions.GET_EDITIONS_ERROR, 
          payload: error,
        }))
      })
    })
  }
}
