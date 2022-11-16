class AddPasswordDigestToAccount < ActiveRecord::Migration[7.0]
  def change
    add_column :accounts, :password_digest, :string
    remove_column :accounts, :password, :string
  end
end
