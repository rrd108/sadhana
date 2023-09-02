<script setup lang="ts">
  import { computed, ref } from 'vue'
  import axios from 'axios'
  import { useStore } from '../store'
  import User from '../types/User'
  import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'

  const store = useStore()
  const users = ref([] as User[])
  const counsellor = ref('')

  axios
    .get(`${import.meta.env.VITE_APP_API_URL}users.json`, store.tokenHeader)
    .then(res => (users.value = res.data.users))
    .catch(err => console.error(err))

  const addCounsellor = () => {
    const selected = users.value.find(
      user => (user.name?.trim() == counsellor.value.trim()) || (user.email.split('@')[0] == counsellor.value)
    )?.id

    if (selected) {
        let counsellorCounsulee = {}
          counsellorCounsulee = {counsellor_id: selected}
          axios.post(
            `${import.meta.env.VITE_APP_API_URL}counsellors-counsulees.json`,
            counsellorCounsulee,
            store.tokenHeader
          )
          .then(res => {
            store.user.counsellors.push(res.data.counsellorsCounsulee.counsellor_id)
            counsellor.value = ''
          })
          .catch(err => console.error(err))
    }
  }

  const removeCounsellor = (counsellor: string) => {
    axios.delete(
      `${import.meta.env.VITE_APP_API_URL}counsellors-counsulees/${counsellor}.json`,
      store.tokenHeader
    ).then(res => {
      store.user.counsellors = store.user.counsellors.filter(c => c != counsellor)
    }).catch(err => console.error(err))
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
    Havonta fog kapni egy összesítőt a sadhana adatokról Śrīla Śivarāma Swāmi Mahārāja, Rādhā Kṛṣṇa Dāsa és Śrīpati Dāsa.</p>
    <p>
    Ha szeretnéd, hogy a tanácsadód, barátod láthassa a sadhanád részleteit,
    akkor azt itt engedélyezheted. E nélkül mindneki más csak a pontszámaidat látja.
  </p>
  
  <p v-if="!store.user.counsellors?.length">Jelenleg rajtad kívűl senkinek sincs hozzáférése.</p>
  <p v-if="store.user.counsellors?.length">Jelenleg rajtad kívűl ők férnek még hozzá a részletekhez:
    <ul>
        <li v-for="counsellor in store.user.counsellors">
           <button @click="removeCounsellor(counsellor)"><font-awesome-icon icon="circle-minus" /></button>
            <span>{{ users.find(user => user.id == counsellor)?.name || users.find(user => user.id == counsellor)?.email.split('@')[0] }}</span>
        </li>
    </ul>
</p>

  <label for="counsellor">Lássa még a részleteket:</label>
  <fieldset>
    <input
      id="counsellor"
      list="users"
      placeholder="Felhasználó"
      v-model="counsellor"
    />
    <datalist id="users">
      <option v-for="user in noAccessUsers" :key="user.id" :value="user.name || user.email.split('@')[0]" />
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
