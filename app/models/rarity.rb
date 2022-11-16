class Rarity < ApplicationRecord
  def self.draw
    pool = []
    self.all.each do |rarity|
      rarity.chance.times { pool << rarity }
    end
    pool.sample
  end

  def to_s
    name.titleize
  end

  def chance_percentage
    (100 * chance.to_d / self.class.all.pluck(:chance).sum.to_d).round 2
  end

  def chance_percentage_s
    '%.2f' % chance_percentage + '%'
  end
end
