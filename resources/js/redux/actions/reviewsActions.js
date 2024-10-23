
import HttpService from '../../services/HttpService'
import { reviews, } from '../types'

export const getReviews = (bookID, page=null) => {
  return async dispatch => {
    const http = new HttpService()

    dispatch({ type: reviews.GET_REVIEWS_PENDING, })

    const path = page ? 
      "/books/"+bookID+"/reviews?page="+page : 
      "/books/"+bookID+"/reviews"
    await new Promise((resolve, reject) => {
      http.getData(http.domain+'/sanctum/csrf-cookie').then( 
        http.getData(path).then(res => {
          resolve(dispatch({
            type: reviews.GET_REVIEWS_SUCCESS,
            payload: res.data,
          }))                
        }, error => {
          reject(dispatch({ 
            type : reviews.GET_REVIEWS_ERROR, 
            payload: error,
          }))
        })
      ).catch(error => {
        reject(dispatch({ 
          type : reviews.GET_REVIEWS_ERROR, 
          payload: error,
        }))
      })
    })
  }
}
