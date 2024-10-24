import React from 'react'
import { Helmet, } from "react-helmet"

export default function NotFoundComponent() {
  console.log(1)
  return (
    <>
      <div className='container not-found-container'>
        <Helmet>
            <title>404 Not Found | {import.meta.env.VITE_APP_NAME}</title>
        </Helmet>
        <h1><pre>404 | Not Found</pre></h1>
      </div>
    </>
  )
}
