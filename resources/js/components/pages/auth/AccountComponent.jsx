import React, { useEffect, useState, } from 'react'
import { useNavigate, } from 'react-router-dom'
import { useDispatch, useSelector, } from 'react-redux'
import { Helmet, } from "react-helmet"
import { register, authorize, } from '../../../redux/actions/authActions'

import "./AccountComponent.scss"

export default function AccountComponent() {
  const navigate = useNavigate()
  const state = useSelector(state => ({
    auth: state.auth,
  }))
  const [name, setName] = useState("")
  const [email, setEmail] = useState("")
  const [newPassword, setNewPassword] = useState("")
  const [newPasswordConfirmation, setNewPasswordConfirmation] = useState("")
  const [password, setPassword] = useState("")
  const [passwordConfirmation, setPasswordConfirmation] = useState("")

  const dispatch = useDispatch()
  const authState = useSelector(state => (state.auth))

  useEffect(() => {
    dispatch(authorize())
  }, [])

  useEffect(() => {
    if (
      !state.auth.loading &&
      null === state.auth.data
    ) {
      navigate("/user/login")
    } else if (
      !state.auth.loading &&
      typeof state.auth.data === "object" &&
      null !== state.auth.data
    ) {
      setName(state.auth.data.name)
      setEmail(state.auth.data.email)
    }
  }, [state.auth])

  const onFormSubmit = (e) => {
    e.preventDefault()

    dispatch(register({
      password_confirmation: passwordConfirmation,
      name,
      email,
      password
    }))

    setName("")
    setEmail("")
    setPassword("")
    setPasswordConfirmation("")
  }

  const onNameChange = (e) => {
    setName(e.target.value)
  }

  const onEmailChange = (e) => {
    setEmail(e.target.value)
  }

  const onChangePasswordChange = (e) => {
    setNewPassword(e.target.value)
  }

  const onChangePasswordConfirmationChange = (e) => {
    setNewPasswordConfirmation(e.target.value)
  }

  const onPasswordChange = (e) => {
    setPassword(e.target.value)
  }

  const onPasswordConfirmationChange = (e) => {
    setPasswordConfirmation(e.target.value)
  }

  if (
    !state.auth.loading &&
    typeof state.auth.data === "object" &&
    null !== state.auth.data
  ) {
    console.log("auth", state.auth.data)
  }

  if (state.auth.loading) {
    return <div className='container login-container text-center'>
      <Helmet>
          <title>Account | {import.meta.env.VITE_APP_NAME}</title>
      </Helmet>
      <p>Loading...</p>
    </div>
  }

  return (
    <>
      <div className='container login-container'>
        <Helmet>
            <title>Account | {import.meta.env.VITE_APP_NAME}</title>
        </Helmet>
        <div className="row">
          <div className="col-md-4 offset-md-4">
            <h3 className="lead">Account</h3>
          </div>
        </div>
        <form className="row" onSubmit={onFormSubmit} method="post" >
          <div className="col-md-4 offset-md-4">
            {authState.error ?
              <div className="alert alert-warning alert-dismissible fade show" role="alert">
                {authState.error}
                <button type="button" className="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
              </div> : null}
            <div className="form-group">
              <label htmlFor="name">Name</label>
              <input 
                name="name"
                className="form-control"
                value={name}
                onChange={onNameChange}
              />
            </div>
            <div className="form-group">
              <label htmlFor="email">Email</label>
              <input 
                name="email" 
                className="form-control"
                value={email}
                onChange={onEmailChange}
              />
            </div>
            <div className="form-group">
              <label htmlFor="password">Change Password</label>
              <input 
                type="password"
                name="newPassword" 
                className="form-control"
                value={newPassword}
                onChange={onChangePasswordChange}
              />
            </div>
            <div className="form-group">
              <label htmlFor="password">Change Password Confirmation</label>
              <input 
                type="password"
                name="newPasswordConfirmation" 
                className="form-control"
                value={newPasswordConfirmation}
                onChange={onChangePasswordConfirmationChange}
              />
            </div>
            <div className="form-group">
              <label htmlFor="password">Password</label>
              <input 
                type="password"
                name="password" 
                className="form-control"
                value={password}
                onChange={onPasswordChange}
              />
            </div>
            <div className="form-group">
              <label htmlFor="password_confirmation">Password Confirmation</label>
              <input 
                type="password"
                name="password_confirmation" 
                className="form-control"
                value={passwordConfirmation}
                onChange={onPasswordConfirmationChange}
              />
            </div>
            <div className="form-group account-buttons-container">
              <a 
                href="/user/login" 
                className="btn btn-primary login-button"
              >
                Login
              </a>
              <input 
                type="submit" 
                className="btn btn-success" 
              />
            </div>
          </div>
        </form>
      </div>
    </>       
  )
}
