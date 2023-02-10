<script setup lang="ts"></script>

<template>
  <h1>2023. február 6 -12.</h1>
  <ul>
    <li><span>1</span><span>Bubu</span><span class="right">672</span></li>
    <li><span>2</span><span>Gulab</span><span class="right">670</span></li>
    <li><span>3</span><span>Csocsó</span><span class="right">650</span></li>
    <li><span>4</span><span>Tomi</span><span class="right">400</span></li>
    <li><span>5</span><span>Lali</span><span class="right">350</span></li>
    <li><span>6</span><span>Sandes</span><span class="right">344</span></li>
    <li><span>7</span><span>Ganga</span><span class="right">340</span></li>
    <li class="me">
      <span>8</span><span>rrd</span><span class="right">108</span>
    </li>
    <li><span>9</span><span>Tamasz</span><span class="right">107</span></li>
  </ul>
</template>

<style scoped>
  h1 {
    margin-bottom: 2rem;
  }
  li {
    display: grid;
    grid-template-columns: 1fr 4fr 1fr;
    margin-bottom: 0.5em;
    background-color: var(--pinky);
    border-radius: 0.5em;
    padding: 0.25em 0.5em;
    color: var(--dark-purple);
  }
  .right {
    text-align: right;
  }
  .me {
    background-color: #fff;
    color: #000;
  }
</style>
