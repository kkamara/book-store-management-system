
import HttpService from '../../services/HttpService'
import { categories, } from '../types'

export const getCategories = () => {
  return async dispatch => {
    const http = new HttpService()

    dispatch({ type: categories.GET_CATEGORIES_PENDING, })

    const path = "/books/search/categories"
    await new Promise((resolve, reject) => {
      http.getData(http.domain+'/sanctum/csrf-cookie').then( 
        http.getData(path).then(res => {
          resolve(dispatch({
            type: categories.GET_CATEGORIES_SUCCESS,
            payload: res.data,
          }))                
        }, error => {
          reject(dispatch({ 
            type : categories.GET_CATEGORIES_ERROR, 
            payload: error,
          }))
        })
      ).catch(error => {
        reject(dispatch({ 
          type : categories.GET_CATEGORIES_ERROR, 
          payload: error,
        }))
      })
    })
  }
}
