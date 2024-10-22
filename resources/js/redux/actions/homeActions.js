
import HttpService from '../../services/HttpService'
import { home, } from '../types'

export const getHome = page => {
  return async dispatch => {
    const http = new HttpService()
        
    dispatch({ type: home.GET_HOME_PENDING, })

    const tokenId = "user-token"
    const path = page ? '/?page='+page : ''
    await new Promise((resolve, reject) => {
      http.getData(http.domain+'/sanctum/csrf-cookie').then( 
        http.getData(path, tokenId).then(res => {
          resolve(dispatch({
            type: home.GET_HOME_SUCCESS,
            payload: res.data,
          }))                
        }, error => {
          reject(dispatch({ 
            type : home.GET_HOME_ERROR, 
            payload: error,
          }))
        })
      ).catch(error => {
        reject(dispatch({ 
          type : home.GET_HOME_ERROR, 
          payload: error,
        }))
      })
    })
  }
}
