class Account < ApplicationRecord
  has_secure_password
  validates :username, uniqueness: { case_sensitive: false }
  has_many :characters

  def self.guest
    {}
  end

  def draw_character
    character = Character.draw
    character.update account_id: id
    character
  end
end
