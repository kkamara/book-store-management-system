import { editions, } from '../types'

const initState = {
  data: null,
  error: null,
  loading: true,
}

export default function editionsReducer (state = initState, action) {
  switch (action.type) {
    
    case editions.GET_EDITIONS_ERROR:
      return {
        ...state,
        error: action.payload,
        loading: false,
      }
    
    case editions.GET_EDITIONS_PENDING:
      return {
        ...state,
        loading: true,
      }
    
    case editions.GET_EDITIONS_SUCCESS:
      return {
        ...state,
        data: action.payload,
        loading: false,
      }

    default:
      return state
  }
}
