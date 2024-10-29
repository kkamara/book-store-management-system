import { order, } from '../types'

const initState = {
  data: null,
  error: null,
  loading: true,
}

export default function orderReducer (state = initState, action) {
  switch (action.type) {
    
    case order.GET_ORDER_ERROR:
      return {
        ...state,
        error: action.payload,
        loading: false,
      }
    
    case order.GET_ORDER_PENDING:
      return {
        ...state,
        loading: true,
      }
    
    case order.GET_ORDER_SUCCESS:
      return {
        ...state,
        data: action.payload,
        loading: false,
      }

    default:
      return state
  }
}
