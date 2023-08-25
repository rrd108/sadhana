import { expect, test } from 'vitest'
import { toISO } from './getDateData'

test('toISO', () => {
  expect(toISO('2022. 04. 26.')).toBe('2022-04-26')
  expect(toISO('2022/04/26')).toBe('2022-04-26')
})
