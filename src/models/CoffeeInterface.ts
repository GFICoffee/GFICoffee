export default interface CoffeeInterface {
  img: string
  name: string
  desc: string
  intensity: number
  type: 'ristretto' | 'espresso' | 'lungo'
  unitPrice: number
  selected?: boolean
  quantity30?: number | string
  quantity50?: number | string
}