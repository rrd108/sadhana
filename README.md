# Sadhana

## Production

### Badge generation

Badge generation happens with cron jobs.

```bash
api/bin/cake BadgeDistributor -t week   # on Monday mornings
api/bin/cake BadgeDistributor -t day   # on every morning
```

## Start

```
yarn dev
```
