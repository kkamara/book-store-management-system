import { book, } from '../types'

const initState = {
  data: null,
  error: null,
  loading: true,
}

export default function bookReducer (state = initState, action) {
  switch (action.type) {
    
    case book.GET_BOOK_ERROR:
      return {
        ...state,
        error: action.payload,
        loading: false,
      }
    
    case book.GET_BOOK_PENDING:
      return {
        ...state,
        loading: true,
      }
    
    case book.GET_BOOK_SUCCESS:
      return {
        ...state,
        data: action.payload,
        loading: false,
      }

    default:
      return state
  }
}
