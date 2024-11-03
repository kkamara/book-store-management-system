import React, { useEffect, useState, } from 'react'
import { useNavigate, } from 'react-router-dom'
import { useDispatch, useSelector, } from 'react-redux'
import { Helmet, } from "react-helmet"
import { authorize, } from '../../../redux/actions/authActions'
import { update, } from '../../../redux/actions/updateAccountActions'

import "./AccountComponent.scss"

export default function AccountComponent() {
  const navigate = useNavigate()
  const state = useSelector(state => ({
    auth: state.auth,
    updateAccount: state.updateAccount,
  }))
  const [name, setName] = useState("")
  const [email, setEmail] = useState("")
  const [newPassword, setNewPassword] = useState("")
  const [newPasswordConfirmation, setNewPasswordConfirmation] = useState("")
  const [password, setPassword] = useState("")
  const [passwordConfirmation, setPasswordConfirmation] = useState("")

  const dispatch = useDispatch()

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
      null !== state.auth.data &&
      !state.auth.error
    ) {
      setName(state.auth.data.name)
      setEmail(state.auth.data.email)
      setPassword("")
      setPasswordConfirmation("")
      setNewPassword("")
      setNewPasswordConfirmation("")
    }
  }, [state.auth])

  useEffect(() => {
    if (
      !state.updateAccount.loading &&
      typeof state.updateAccount.data === "object" &&
      null !== state.updateAccount.data &&
      !state.updateAccount.error
    ) {
      setName(state.updateAccount.data.name)
      setEmail(state.updateAccount.data.email)
      setPassword("")
      setPasswordConfirmation("")
      setNewPassword("")
      setNewPasswordConfirmation("")
    }
  }, [state.updateAccount])

  const onFormSubmit = (e) => {
    e.preventDefault()

    dispatch(update({
      change_password: newPassword,
      change_password_confirmation: newPasswordConfirmation,
      password_confirmation: passwordConfirmation,
      name,
      email,
      password,
    }))
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

  if (
    !state.updateAccount.loading &&
    typeof state.updateAccount.data === "object" &&
    null !== state.updateAccount.data
  ) {
    console.log("updateAccount", state.updateAccount.data)
  }

  if (state.auth.loading || state.updateAccount.loading) {
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
            {state.updateAccount.error ?
              <div className="alert alert-warning alert-dismissible fade show" role="alert">
                {state.updateAccount.error}
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
