class RemoveNameFromCharacter < ActiveRecord::Migration[7.0]
  def change
    remove_column :characters, :name, :string
  end
end
