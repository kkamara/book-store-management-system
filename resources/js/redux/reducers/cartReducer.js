import { cart, } from '../types'

const initState = {
  data: null,
  error: null,
  loading: true,
}

export default function cartReducer (state = initState, action) {
  switch (action.type) {
    
    case cart.GET_CART_ERROR:
      return {
        ...state,
        error: action.payload,
        loading: false,
      }
    
    case cart.GET_CART_PENDING:
      return {
        ...state,
        loading: true,
      }
    
    case cart.GET_CART_SUCCESS:
      return {
        ...state,
        data: action.payload,
        loading: false,
      }

    default:
      return state
  }
}
