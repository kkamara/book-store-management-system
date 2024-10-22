import { combineReducers, } from 'redux'
import authReducer from './authReducer'
import usersReducer from './usersReducer'
import homeReducer from './homeReducer'

export default combineReducers({
  auth: authReducer,
  users: usersReducer,
  home: homeReducer,
})
