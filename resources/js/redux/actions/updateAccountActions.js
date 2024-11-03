
import { UpdateUserService, } from '../../services/AuthService'
import { updateAccount, } from '../types'

export const update = data => {
  return dispatch => {
        
    dispatch({ type: updateAccount.UPDATE_ACCOUNT_PENDING, })

    const payload = {}
    if (data.name) {
      payload.name = data.name
    }
    if (data.email) {
      payload.email = data.email
    }
    if (data.password) {
      payload.password = data.password
    }
    if (data.password_confirmation) {
      payload.password_confirmation = data.password_confirmation
    }
    if (data.change_password) {
      payload.change_password = data.change_password
    }
    if (data.change_password_confirmation) {
      payload.change_password_confirmation = data.change_password_confirmation
    }

    UpdateUserService(payload).then(res => {
      dispatch({
        type: updateAccount.UPDATE_ACCOUNT_SUCCESS,
        payload: res,
      })
    }, error => {
      const message = (error.response.data && error.response.data[0]) ||
        (error.response.data.name && error.response.data.name[0]) ||
        (error.response.data.email && error.response.data.email[0]) ||
        (error.response.data.password && error.response.data.password[0]) ||
        (error.response.data.change_password && error.response.data.change_password[0]) ||
        (error.response.data.password_confirmation && error.response.data.password_confirmation[0])
        (error.response.data.change_password_confirmation && error.response.data.change_password_confirmation[0])
      dispatch({ 
        type : updateAccount.UPDATE_ACCOUNT_ERROR, 
        payload: message,
      })
    })
  }
}