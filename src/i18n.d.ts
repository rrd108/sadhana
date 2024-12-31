declare module 'i18n' {
    export const SUPPORT_LOCALES: string[]
    export function setLocale(locale: string): Promise<void>
} 