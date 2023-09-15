# Sadhana

## Production

### Badge generation

Badge generation happens with cron jobs.

```bash
api/bin/cake BadgeDistributor -lt week   # on Monday mornings 4:12
api/bin/cake BadgeDistributor -lt day   # on every morning 3:12
```

## Start

```
yarn dev
```

# How to add a new Language

- [ ] Download flag icon of new language
      https://www.svgrepo.com/collection/decathlon-payment-vectors
      and place in `public/flag-[locale].svg` using that name scheme is nice.
- [ ] Create locale file using 'hu' as template
    `cp src/locale/hu.json src/locale/[your-locale].json`
- [ ] add your locale code to `src/i18n.js` SUPPORT_LOCALES array
- [ ] add the flag to the website
    open `src/components/LocaleFlags.vue`
    add a new img in the same way as the others
    `<img @click="setLocale('[locale]')" src="flag-[locale].svg" />`

replace [locale] with the new locale code e.g. "ru"
