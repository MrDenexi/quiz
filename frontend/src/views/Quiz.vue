<template>
  <div class="home">
    <ol>
      <li
        v-for="(item, key) in quizItems"
        :key="`quiz-item-${key}`"
      >
        {{ item.data.name }}
        <button>
          <router-link :to="{ name: 'QuizSession', params: { id: item.data.id }}"> Start de quiz nu! </router-link>
        </button>
      </li>
    </ol>
  </div>
</template>

<script>
import { useAxios } from '@vue-composable/axios'
import { computed } from 'vue'

export default {
  name: 'Home',
  components: {
    //
  },
  setup () {
    const { data, exec } = useAxios()

    exec({
      method: 'GET',
      url: '//localhost:8080/api/quiz'
    })

    const quizItems = computed(() => data.value ? data.value.data : [])

    return {
      data,
      quizItems
    }
  }
}
</script>
