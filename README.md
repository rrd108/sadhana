# Sadhana

## Production

### Badge generation

Badge generation happens with cron jobs.

```bash
api/bin/cake BadgeDistributor -lt week   # on Monday mornings
api/bin/cake BadgeDistributor -lt day   # on every morning
```

## Start

```
yarn dev
```
