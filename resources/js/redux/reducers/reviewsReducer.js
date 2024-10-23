import { reviews, } from '../types'

const initState = {
  data: null,
  error: null,
  loading: true,
}

export default function reviewsReducer (state = initState, action) {
  switch (action.type) {
    
    case reviews.GET_REVIEWS_ERROR:
      return {
        ...state,
        error: action.payload,
        loading: false,
      }
    
    case reviews.GET_REVIEWS_PENDING:
      return {
        ...state,
        loading: true,
      }
    
    case reviews.GET_REVIEWS_SUCCESS:
      return {
        ...state,
        data: action.payload,
        loading: false,
      }

    default:
      return state
  }
}
