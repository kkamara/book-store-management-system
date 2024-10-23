import React from 'react'
import { Routes, Route, } from 'react-router-dom'

import Header from './components/layouts/Header'
import Footer from './components/layouts/Footer'

import Home from "./components/pages/HomeComponent"
import Login from "./components/pages/auth/LoginComponent"
import Logout from "./components/pages/auth/LogoutComponent"
import Register from "./components/pages/auth/RegisterComponent"
import Book from "./components/pages/book/BookComponent"
import NotFound from "./components/pages/http/NotFoundComponent"

import { url } from './utils/config'

export default () => {
  return (
    <>
      <Header/>
      <Routes>
        <Route path={url("/")} element={<Home />}/>
        <Route path={url("/notfound")} element={<NotFound />}/>
        <Route path={url("/books/:book")} element={<Book />}/>
        <Route path={url("/user/login")} element={<Login />}/>
        <Route path={url("/user/logout")} element={<Logout />}/>
        <Route path={url("/user/register")} element={<Register />}/>
      </Routes>
      <Footer/>
    </>
  )
}
