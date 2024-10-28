import { orders, } from '../types'

const initState = {
  data: null,
  error: null,
  loading: true,
}

export default function ordersReducer (state = initState, action) {
  switch (action.type) {
    
    case orders.GET_ORDERS_ERROR:
      return {
        ...state,
        error: action.payload,
        loading: false,
      }
    
    case orders.GET_ORDERS_PENDING:
      return {
        ...state,
        loading: true,
      }
    
    case orders.GET_ORDERS_SUCCESS:
      return {
        ...state,
        data: action.payload,
        loading: false,
      }

    default:
      return state
  }
}
