import React from 'react'
import { Routes, Route, } from 'react-router-dom'

import Header from './components/layouts/Header'
import Footer from './components/layouts/Footer'

import Home from "./components/pages/HomeComponent"
import Login from "./components/pages/auth/LoginComponent"
import Logout from "./components/pages/auth/LogoutComponent"
import Register from "./components/pages/auth/RegisterComponent"
import Book from "./components/pages/book/BookComponent"
import Orders from "./components/pages/orders/OrdersComponent"
import Order from "./components/pages/order/OrderComponent"
import SearchBooks from "./components/pages/books/SearchBooksComponent"
import Account from "./components/pages/auth/AccountComponent"
import NotFound from "./components/pages/http/NotFoundComponent"

import { url } from './utils/config'

export default () => {
  return (
    <>
      <Header/>
      <Routes>
        <Route path={url("/")} element={<Home />}/>
        <Route path={url("/notfound")} element={<NotFound />}/>
        <Route path={url("/orders")} element={<Orders />}/>
        <Route path={url("/orders/:referenceNumber")} element={<Order />}/>
        <Route path={url("/books/search")} element={<SearchBooks />}/>
        <Route path={url("/books/:slug")} element={<Book />}/>
        <Route path={url("/user/login")} element={<Login />}/>
        <Route path={url("/user/logout")} element={<Logout />}/>
        <Route path={url("/user/register")} element={<Register />}/>
        <Route path={url("/user/account")} element={<Account />}/>
      </Routes>
      <Footer/>
    </>
  )
}
