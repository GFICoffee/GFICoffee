import CoffeeType from '@/api/model/CoffeeType'

export default interface Coffee {
  id?: number
  img?: string
  name?: string
  desc?: string
  intensity?: number
  type: CoffeeType
  unit_price?: number
  selected?: boolean
  quantity30?: number | string
  quantity50?: number | string
}
