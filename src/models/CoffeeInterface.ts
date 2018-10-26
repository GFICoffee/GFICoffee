export default interface CoffeeInterface {
  img: string,
  name: string,
  desc: string,
  intensity: number,
  type: 'ristretto' | 'espresso' | 'lungo'
}