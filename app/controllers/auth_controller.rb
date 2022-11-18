class AuthController < ApplicationController
  def login
    redirect_to(profiles_path) and return if helpers.signed_in?
  end

  def signup
    redirect_to(profiles_path) and return if helpers.signed_in?
  end

  def signup_handler
    account = Account.new(username: params[:username].downcase)
    account.password = params[:password]
    saved = account.save
    session[:account_id] = account.id if saved

    flash[:notice] = 'User already exists!' unless saved
    redirect_to(profiles_path) and return if saved
    redirect_to signup_path
  end

  def login_handler
    flash[:notice] = 'Wrong username/password!' unless matching_password?
    session[:account_id] = account.id if matching_password?

    redirect_to(profiles_path) and return if matching_password?
    redirect_to login_path
  end

  def logout
    session.delete(:account_id) if helpers.signed_in?
    redirect_to login_path
  end

  private

  def account
    Account.find_by(username: params[:username].downcase)
  end

  def matching_password?
    @matching_password = !!account&.authenticate(params[:password])
  end
end
