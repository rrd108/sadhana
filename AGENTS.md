# AGENTS.md - Development Guide for Sadhana

## Project Overview

**Vue 3 + TypeScript + Vite** SPA with **CakePHP** backend. Uses Pinia, Vue Router, Vue I18n, and Vitest.

---

## Commands

```bash
# Install dependencies
npm install

# Start dev server
npm run dev

# Build for production (runs typecheck)
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
```

Backend in `api/` — see `api/README.md`.

---

## Code Style Guidelines

### TypeScript

- Strict mode enabled — do not disable strict checks
- Use explicit types for function parameters and return values
- Use `interface` for object shapes, `type` for unions/aliases
- Prefer `export default` for single exports

### Vue Components

- Use `<script setup lang="ts">` syntax
- Order imports: Vue core → external libs → internal modules
- PascalCase for component names, kebab-case in templates
- Use `defineProps` and `defineEmits` with type syntax

### Naming Conventions

| Type | Convention | Example |
|------|------------|---------|
| Variables/functions | camelCase | `getData()` |
| Components/types | PascalCase | `DayPage`, `UserType` |
| Files | kebab-case | `get-date-data.ts` |
| Constants | SCREAMING_SNAKE_CASE | `MAX_RETRIES` |
| CSS classes | kebab-case | `.btn-primary` |

### Imports

- Use `@` alias for `src/` (e.g., `import Day from '@/pages/Day.vue'`)
- Group: Vue → external → internal
- Explicit extensions in relative imports (`.ts` not `.js`)

### Formatting

- 2 spaces indentation
- Single quotes (template literals excepted)
- Trailing commas in objects/arrays
- No semicolons
- Prefer `const` over `let`

### Error Handling

- `console.error()` for logging
- User messages via `vue-toastification`: `toast.error()`, `toast.warning()`
- Handle axios errors: `.catch(err => console.error(err))`

---

## State & Routing

### Pinia Store

- Use `defineStore` with option syntax for simple stores
- Access via `useStore()` composable
- Use getters for computed state

### Router

- Lazy-load routes: `() => import('/src/pages/...')`
- Use `meta: { noAuth: true }` for public routes
- Auth handled in `router.beforeEach`

### i18n

- Supported locales: `en-US`, `hu`
- Use `$t('key')` in templates
- Add keys to `src/locale/` JSON files

---

## Testing (Vitest)

- Test files: `*.test.ts` or `*.spec.ts`
- Use `describe`, `test`, `expect` from vitest
- Globals enabled (no imports needed)
- Mock external dependencies as needed
- Runs in jsdom environment

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

## Environment Variables

- Create `.env.development` and `.env.production`
- Access via `import.meta.env.VITE_*`
- Never commit secrets

### Firebase (required)

```
VITE_APP_FIREBASE_APIKEY, VITE_APP_FIREBASE_AUTHDOMAIN,
VITE_APP_FIREBASE_PROJECTID, VITE_APP_FIREBASE_STORAGEBUCKET,
VITE_APP_FIREBASE_MESSAGINGSENDERID, VITE_APP_FIREBASE_APPID,
VITE_APP_FIREBASE_MEASUREMENTID, VITE_APP_FIREBASE_VAPIDKEY
```

---

## Directory Structure

```
src/
├── assets/          # Static assets
├── components/      # Reusable Vue components
├── composables/     # Vue composables
├── locale/          # i18n JSON files
├── pages/           # Page components (routes)
├── types/           # TypeScript interfaces
├── App.vue          # Root component
├── main.ts          # Entry point
├── router.ts        # Vue Router config
├── store.ts         # Pinia store
└── i18n.ts          # i18n setup
api/                 # CakePHP backend
```

---

## Notes

- No ESLint/Prettier — follow existing code style
- Firebase 9.x (compat mode) for auth
- Vue Toastification for notifications
- FontAwesome for icons
- VueUse for composition utilities
