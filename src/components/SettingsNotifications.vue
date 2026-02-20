<script setup lang="ts">
  import { ref } from 'vue'
  import axios from 'axios'
  import { useStore } from '../store'
  import { useToast } from 'vue-toastification'
  import { useI18n } from 'vue-i18n'

  const toast = useToast()
  const { t } = useI18n()

  const store = useStore()

  const notificationPermission = ref(Notification.permission == 'granted')
  const isDenied = ref(Notification.permission == 'denied')
  const time = ref(store.user.notificationTime || '19')

  const saveNotificationTime = () =>
    axios
      .patch(
        `${import.meta.env.VITE_APP_API_URL}users/${store.user.id}.json`,
        {
          notificationTime: notificationPermission.value ? time.value : null,
        },
        store.tokenHeader
      )
      .then(
        res => (store.user.notificationTime = res.data.user.notificationTime)
      )
      .catch(err => console.error(err))

  const requestNotificationPermission = () => {
    if (store.user.notificationTime && notificationPermission.value == false) {
      saveNotificationTime()
      return
    }

    if (Notification.permission == 'granted') {
      notificationPermission.value = true
      saveNotificationTime()
      return
    }

    if (Notification.permission == 'denied') {
      isDenied.value = true
      return
    }

    Notification.requestPermission()
      .then(permission => {
        if (permission == 'granted') {
          notificationPermission.value = true
          if (window.matchMedia('(display-mode: standalone)').matches) {
            navigator.serviceWorker.ready
              .then(registration =>
                registration.showNotification('Sadhana', {
                  body: t('settings.notification.enabled'),
                  icon: 'favicon-32x32.png',
                })
              )
              .catch(err => {
                toast.error(t('settings.notification.error'), err)
              })
          }
          if (!window.matchMedia('(display-mode: standalone)').matches) {
            new Notification('Sadhana', {
              body: t('settings.notification.enabled'),
              icon: 'favicon-32x32.png',
            })
          }
          saveNotificationTime()
        }
      })
      .catch(err => {
        toast.error(t('settings.notification.error'), err)
      })
  }
</script>

<template>
  <h2>{{$t('settings.notification.title')}}</h2>
  <div>
    <label for="notification">{{$t('settings.notification.title')}}</label>
    <input
      id="notification"
      type="checkbox"
      v-model="notificationPermission"
      @change="requestNotificationPermission"
      :disabled="isDenied"
    />
  </div>
  <div v-show="notificationPermission">
    <label for="time">{{$t('settings.notification.time')}}</label>
    <select v-model="time" @change="saveNotificationTime">
      <option v-for="i in 12" :key="i" :value="i + 8">{{ i + 8 }}</option>
    </select>
    {{$t('settings.notification.time_suffix')}}
  </div>
  <p v-if="isDenied" class="info" v-html="$t('settings.notification.denied').replace(/\*(\S[^\*]+\S)\*/g, '<strong>$1</strong>')"></p>
  <div>
    <p v-if="!notificationPermission" v-html="$t('settings.notification.permission').replace(/\*(\S[^\*]+\S)\*/g, '<strong>$1</strong>')"></p>
    <p>{{$t('settings.notification.info')}}</p>
  </div>
</template>

<style scoped>
  input {
    margin-left: 1em;
  }
  select {
    margin-left: 0.5em;
  }
  div {
    display: inline-block;
  }

  input[type='checkbox'] {
    margin-right: 2em;
  }
  input[type='number'] {
    text-align: center;
  }
</style>
