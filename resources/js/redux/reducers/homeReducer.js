import { home, } from '../types'

const initState = {
  data: null,
  error: null,
  loading: false,
}

export default function homeReducer (state = initState, action) {
  switch (action.type) {
    
    case home.GET_HOME_ERROR:
      return {
        ...state,
        error: action.payload,
        loading: false,
      }
    
    case home.GET_HOME_PENDING:
      return {
        ...state,
        loading: true,
      }
    
    case home.GET_HOME_SUCCESS:
      return {
        ...state,
        data: action.payload,
        loading: false,
      }

    default:
      return state
  }
}
