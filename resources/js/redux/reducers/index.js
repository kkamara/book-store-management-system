import { combineReducers, } from 'redux'
import authReducer from './authReducer'
import homeReducer from './homeReducer'
import bookReducer from './bookReducer'
import reviewsReducer from './reviewsReducer'
import ordersReducer from './ordersReducer'
import orderReducer from './orderReducer'
import orderBooksReducer from './orderBooksReducer'
import editionsReducer from './editionsReducer'
import categoriesReducer from './categoriesReducer'
import searchBooksReducer from './searchBooksReducer'
import updateAccountReducer from './updateAccountReducer'
import cartReducer from './cartReducer'

export default combineReducers({
  auth: authReducer,
  home: homeReducer,
  book: bookReducer,
  reviews: reviewsReducer,
  orders: ordersReducer,
  order: orderReducer,
  orderBooks: orderBooksReducer,
  editions: editionsReducer,
  categories: categoriesReducer,
  searchBooks: searchBooksReducer,
  updateAccount: updateAccountReducer,
  cart: cartReducer,
})
