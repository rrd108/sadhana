<script setup lang="ts">
  import { computed, ref } from 'vue'
  import axios from 'axios'
  import { useStore } from '../store'
  import User from '../types/User'
  import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'

  const store = useStore()
  const users = ref([] as User[])
  const userEmail = ref('')

  axios
    .get(`${import.meta.env.VITE_APP_API_URL}users.json`, store.tokenHeader)
    .then(res => (users.value = res.data.users))
    .catch(err => console.error(err))

const patchUser = (counsellorArray: string[]) => {
    axios
        .patch(
          `${import.meta.env.VITE_APP_API_URL}users/${store.user.id}.json`,
          { counsellors: counsellorArray },
          store.tokenHeader
        )
        .then(res => {
          store.user.counsellors = JSON.parse(res.data.user.counsellors)
            userEmail.value = ''
        })
        .catch(err => console.error(err))
}

  const addCounsellor = () => {
    const counsellor = users.value.find(
      user => user.email == userEmail.value
    )?.id
    if (counsellor) {
        let counsellorArray: string[] = []
        if (store.user.counsellors) {
            counsellorArray = [...store.user.counsellors, counsellor]
        }
        if (!store.user.counsellors) {
            counsellorArray = [counsellor]
        }
      patchUser(counsellorArray)
    }
  }

  const removeCounsellor = (counsellor: string) => {
    const counsellorArray = store.user.counsellors?.filter(id => id != counsellor)
    patchUser(counsellorArray)
  }

  const noAccessUsers = computed(() => {
    return users.value.filter(
      user => !store.user.counsellors?.includes(user.id) && user.id != store.user.id
    )
  })
</script>

<template>
  <h2>Hozzáférések</h2>
  <p>
    Ha szeretnéd, hogy a tanácsadód, barátod láthassa a sadhanád részleteit,
    akkor azt itt engedélyezheted. E nélkül mindneki más csak a pontszámaidat látja.
  </p>
  
  <p v-if="!store.user.counsellors?.length">Jelenleg rajtad kívűl senkinek sincs hozzáférése.</p>
  <p v-if="store.user.counsellors?.length">Jelenleg rajtad kívűl ők férnek még hozzá a részletekhez:
    <ul>
        <li v-for="counsellor in store.user.counsellors">
           <button @click="removeCounsellor(counsellor)"><font-awesome-icon icon="circle-minus" /></button>
            <span>{{ users.find(user => user.id == counsellor)?.email }}</span>
        </li>
    </ul>
</p>

  <label for="counsellor">Lássa még a részleteket:</label>
  <fieldset>
    <input
      id="counsellor"
      type="email"
      list="users"
      placeholder="Email cím"
      v-model="userEmail"
    />
    <datalist id="users">
      <option v-for="user in noAccessUsers" :key="user.id" :value="user.email" />
    </datalist>
    <button @click="addCounsellor">
      <font-awesome-icon icon="circle-plus" />
    </button>
  </fieldset>
</template>

<style scoped>
  fieldset {
    display: flex;
    align-items: center;
    border: none;
    margin-bottom: 3em;
  }
  button {
    background: none;
    border: none;
    color: var(--pinky);
    height: 2em;
    width: 2em;
    font-size: 1.5em;
  }
  ul {
    list-style: none;
    margin-left: 1em;
  }
  span {
    margin-left: 1em;
  }
</style>
