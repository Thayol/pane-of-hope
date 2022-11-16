class Account < ApplicationRecord
  has_secure_password
  validates :username, uniqueness: { case_sensitive: false }
  has_many :characters

  def self.guest
    {}
  end

  def draw_character
    Character.draw.update account_id: id
  end
end
