class Character < ApplicationRecord
  belongs_to :character_template
  belongs_to :rarity
  belongs_to :account

  def to_s
    "#{character_template.to_s} (#{rarity.to_s})"
  end

  def self.draw
    Character.new character_template_id: CharacterTemplate.draw.id, rarity_id: Rarity.draw.id
  end
end
