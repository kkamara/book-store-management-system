import { updateAccount, } from '../types'

const initState = {
  data: null,
  error: null,
  loading: false,
}

export default function updateAccountReducer (state = initState, action) {
  switch (action.type) {
    
    case updateAccount.UPDATE_ACCOUNT_ERROR:
      return {
        ...state,
        error: action.payload,
        loading: false,
        data: null
      }
    
    case updateAccount.UPDATE_ACCOUNT_PENDING:
      return {
        ...state,
        loading: true,
      }
    
    case updateAccount.UPDATE_ACCOUNT_SUCCESS:
      return {
        ...state,
        data: action.payload,
        loading: false,
      }

    default:
      return state
  }
}
