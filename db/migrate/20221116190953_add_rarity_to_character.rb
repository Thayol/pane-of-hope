class AddRarityToCharacter < ActiveRecord::Migration[7.0]
  def change
    add_reference :characters, :rarity, null: false, foreign_key: true
  end
end
