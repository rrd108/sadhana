# AGENTS.md - Development Guide for Sadhana

## Project Overview

This is a **Vue 3 + TypeScript + Vite** SPA with a **CakePHP** backend API. The frontend uses Pinia for state management, Vue Router, Vue I18n, and Vitest for testing.

---

## Commands

### Frontend (Vue/Vite)

```bash
# Install dependencies
npm install

# Start dev server
npm run dev

# Build for production
npm run build

# Preview production build
npm run preview

# Run all tests
npm run test

# Run tests with coverage
npm run coverage

# Run a single test file
npm run test -- src/composables/getDateData.test.ts

# Run a single test (alternative)
npx vitest run src/composables/getDateData.test.ts

# Deploy
npm run deploy
```

### Backend (CakePHP)

Located in `api/` directory. See `api/README.md` for details.

---

## Code Style Guidelines

### TypeScript

- **Strict mode enabled** in `tsconfig.json` - do not disable strict checks
- Use explicit types for function parameters and return values
- Use `interface` for object shapes, `type` for unions/aliases
- Prefer `export default` for single exports (components, types)

### Vue Components

- Use `<script setup lang="ts">` syntax
- Order imports: Vue core → external libs → internal modules
- Use PascalCase for component names, kebab-case in templates
- Use `defineProps` and `defineEmits` with type syntax

### Naming Conventions

- **Variables/functions**: camelCase
- **Components/classes/types**: PascalCase
- **Files**: kebab-case (e.g., `get-date-data.ts`, `login-form.vue`)
- **Constants**: SCREAMING_SNAKE_CASE
- **CSS classes**: kebab-case

### Imports

- Use `@` alias for `src/` directory (e.g., `import Day from '@/pages/Day.vue'`)
- Group imports: Vue → external → internal
- Use explicit extensions in relative imports (e.g., `./store.ts` not `./store`)

### Error Handling

- Use `console.error()` for logging errors
- Display user-friendly messages via `vue-toastification` (`toast.error()`, `toast.warning()`)
- Handle axios errors with `.catch(err => console.error(err))`

### Formatting

- 2 spaces for indentation
- Single quotes for strings (except where template literals needed)
- Trailing commas in objects/arrays
- No semicolons at end of statements
- Prefer const over let

### State Management (Pinia)

- Use `defineStore` with the option syntax for simple stores
- Access store via `useStore()` composable
- Use getters for computed state

### Routing

- Lazy-load routes with `() => import('/src/pages/...')`
- Use `meta: { noAuth: true }` for public routes
- Handle auth in `router.beforeEach` (see `router.ts`)

### Internationalization (i18n)

- Supported locales: `en-US`, `hu`
- Use `$t('key')` in templates
- Add translation keys to locale JSON files in `src/locale/`

### Testing (Vitest)

- Test files: `*.test.ts` or `*.spec.ts`
- Use `describe`, `test`, `expect` from vitest
- Use `globals: true` (globals available without import)
- Mock external dependencies as needed
- Tests run in jsdom environment

### Environment Variables

- Create `.env.development` and `.env.production`
- Access via `import.meta.env.VITE_*`
- Never commit secrets

---

## Directory Structure

```
src/
├── assets/          # Static assets
├── components/      # Reusable Vue components
├── composables/     # Vue composables (functions)
├── locale/          # i18n JSON files
├── pages/           # Page-level components (routes)
├── types/           # TypeScript interfaces/types
├── App.vue          # Root component
├── main.ts          # Entry point
├── router.ts        # Vue Router config
├── store.ts         # Pinia store
├── style.css        # Global styles
└── i18n.ts          # i18n setup
api/                 # CakePHP backend
  └── python/         # Push notification scripts
```

---

## Common Patterns

### API Calls

```typescript
axios.get(`${import.meta.env.VITE_APP_API_URL}endpoint.json`, store.tokenHeader)
  .then(res => { /* handle */ })
  .catch(err => { console.error(err); toast.error('Error') })
```

### Reactive State

```typescript
const count = ref(0)
const doubled = computed(() => count.value * 2)
```

### Store Access

```typescript
import { useStore } from '@/store'
const store = useStore()
```

---

## Push Notifications

The app uses Firebase Cloud Messaging (FCM) for push notifications.

### Components

- **`src/composables/useFirebaseToken.ts`** - Token refresh logic
- **`src/components/SettingsNotifications.vue`** - User permission UI
- **`api/python/reminders.py`** - Cron job that sends notifications and cleans up invalid tokens

### How It Works

1. User enables notifications in Settings → permission requested, token stored in DB
2. On every app open (`Day.vue`), token is refreshed automatically via `useFirebaseToken()`
3. Python cron runs hourly, sends reminders to users who haven't logged sadhana
4. If FCM returns invalid/expired token errors, the Python script removes them from DB

### Token Management

- FCM tokens expire regularly (weeks/months)
- Always refresh token on app open - compare with stored value and update if different
- Handle `NOT_FOUND`, `INVALID_ARGUMENT`, `UNREGISTRED` errors in backend and remove invalid tokens

### Environment Variables Required

```bash
VITE_APP_FIREBASE_APIKEY
VITE_APP_FIREBASE_AUTHDOMAIN
VITE_APP_FIREBASE_PROJECTID
VITE_APP_FIREBASE_STORAGEBUCKET
VITE_APP_FIREBASE_MESSAGINGSENDERID
VITE_APP_FIREBASE_APPID
VITE_APP_FIREBASE_MEASUREMENTID
VITE_APP_FIREBASE_VAPIDKEY
```

---

## Notes

- No ESLint/Prettier config present - follow existing code style
- Firebase is used for auth (version 9.x, compat mode)
- Vue Toastification for notifications
- FontAwesome for icons
- VueUse for composition utilities
