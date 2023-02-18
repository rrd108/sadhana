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
