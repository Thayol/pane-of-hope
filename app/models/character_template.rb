class CharacterTemplate < ApplicationRecord
  def self.draw
    self.all.sample
  end

  def to_s
    name
  end
end
