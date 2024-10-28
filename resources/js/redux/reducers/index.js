import { combineReducers, } from 'redux'
import authReducer from './authReducer'
import homeReducer from './homeReducer'
import bookReducer from './bookReducer'
import reviewsReducer from './reviewsReducer'
import ordersReducer from './ordersReducer'

export default combineReducers({
  auth: authReducer,
  home: homeReducer,
  book: bookReducer,
  reviews: reviewsReducer,
  orders: ordersReducer,
})
