import { combineReducers, } from 'redux'
import authReducer from './authReducer'
import usersReducer from './usersReducer'
import homeReducer from './homeReducer'
import bookReducer from './bookReducer'
import reviewsReducer from './reviewsReducer'

export default combineReducers({
  auth: authReducer,
  users: usersReducer,
  home: homeReducer,
  book: bookReducer,
  reviews: reviewsReducer,
})
