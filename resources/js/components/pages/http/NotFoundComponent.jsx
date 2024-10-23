import React, { useEffect, } from 'react'
import moment from 'moment'

export default function NotFoundComponent() {
  console.log(1)
  return (
    <>
      <div className='container not-found-container'>
        <h1><pre>404 | Not Found</pre></h1>
      </div>
    </>
  )
}
