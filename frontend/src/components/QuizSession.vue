<template>
  <div class="quiz-session">
    <quiz-question v-for="q in questions" :key="`question-${q.data.id}`" :question="q" />
    <p>
      <button @click="prev"> vorige </button>
      <button @click="next"> volgende </button>
    </p>
    <p> vraag: {{ meta.current_page }} / {{ meta.total }} </p>
  </div>
</template>

<script>
import { useAxios } from '@vue-composable/axios'
import { computed, ref } from 'vue'
import QuizQuestion from '@/components/QuizQuestion'

export default {
  name: 'QuizSession',
  components: {
    QuizQuestion
  },
  props: {
    id: {
      type: String,
      required: true
    },
    apiPath: {
      type: String,
      required: false
    }
  },
  setup (props) {
    const { data, exec, loading } = useAxios()

    const apiUrl = ref('//localhost:8080/api/quiz/' + props.id)
    const getQuestions = () => exec({
      method: 'GET',
      url: apiUrl.value
    })
    getQuestions()

    const questions = computed(() => {
      if (data.value && data.value.data) {
        return data.value.data
      }
      return []
    })

    const links = computed(() => data.value ? data.value.links : {})

    const meta = computed(() => data.value ? data.value.meta : {})

    const prev = () => {
      if (links.value) {
        apiUrl.value = links.value.prev
        getQuestions()
      }
    }

    const next = () => {
      if (links.value) {
        apiUrl.value = links.value.next
        getQuestions()
      }
    }

    return {
      getQuestions,
      links,
      loading,
      next,
      prev,
      questions,
      meta
    }
  }
}
</script>
