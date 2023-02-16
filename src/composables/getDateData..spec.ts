import { toISO } from '@/composables/getDateData'

describe('toISO', () => {
  it('should find the tags', () => {
    expect(toISO('2022. 04. 26.')).toBe('2022-04-26')
    expect(toISO('2022/04/26')).toBe('2022-04-26')
  })
})
