module ApplicationHelper
  def signed_in?
    session[:account_id].present? && session[:account_id].positive?
  end

  def account
    return Account.guest unless signed_in?

    Account.find session[:account_id]
  end
end
