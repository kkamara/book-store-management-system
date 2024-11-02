import { categories, } from '../types'

const initState = {
  data: null,
  error: null,
  loading: true,
}

export default function categoriesReducer (state = initState, action) {
  switch (action.type) {
    
    case categories.GET_CATEGORIES_ERROR:
      return {
        ...state,
        error: action.payload,
        loading: false,
      }
    
    case categories.GET_CATEGORIES_PENDING:
      return {
        ...state,
        loading: true,
      }
    
    case categories.GET_CATEGORIES_SUCCESS:
      return {
        ...state,
        data: action.payload,
        loading: false,
      }

    default:
      return state
  }
}
