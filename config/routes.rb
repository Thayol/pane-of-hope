Rails.application.routes.draw do
  get 'login', to: 'auth#login'
  post 'login_handler', to: 'auth#login_handler'
  get 'signup', to: 'auth#signup'
  post 'signup_handler', to: 'auth#signup_handler'
  get 'logout', to: 'auth#logout'

  get 'profiles', to: 'profiles#index'
  # Define your application routes per the DSL in https://guides.rubyonrails.org/routing.html

  # Defines the root path route ("/")
  # root "articles#index"
end
