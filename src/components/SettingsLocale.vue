<script setup lang="ts">
  import LocaleChanger from '../components/LocaleChanger.vue'
</script>


<template>
  {{$t('settings.locale_info')}}
  <LocaleChanger />
</template>
