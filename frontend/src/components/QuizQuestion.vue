<template>
  <div class="quiz-session">
    <h2> {{ q.content }} </h2>
    <h5> {{ stateMessage }} </h5>
    <ul>
      <li v-for="answer in q.answers" :key="`answer-${answer.id}`">
        <label>
          <input v-model="q.answer_id" type="radio" :value="answer.id" />
          {{ answer.content }}
        </label>
      </li>
    </ul>
    <p v-if="q.state === 'ACTIVE'">
      Antwoord binnen {{ secondsLeft }} seconden!
    </p>
    <button @click="submit"> Submit! </button> <span v-if="loading"> laden... </span>
  </div>
</template>

<script>
import { useAxios } from '@vue-composable/axios'
import { ref, computed } from 'vue'
import countdown from '@/hooks/countdown'

export default {
  name: 'QuizQuestion',
  props: {
    question: {
      type: Object,
      required: true
    }
  },
  setup (props) {
    const { data, exec, loading } = useAxios()

    const q = ref(props.question.data)
    const self = ref(props.question.links.self)
    const submit = () => exec({
      method: 'PATCH',
      url: self.value,
      params: {
        answer_id: q.value.answer_id
      }
    }, true).then((response) => {
      q.value = data.value.data
    })

    const { secondsLeft, promise: countdownPromise } = countdown(q.value.time_limit)

    countdownPromise.then(() => {
      if (q.value && q.value.state === 'ACTIVE') {
        submit()
      }
    })

    const stateMessage = computed(() => {
      const messages = {
        ACTIVE: 'De tijd loopt. Antwoord snel!',
        ANSWER_WRONG: 'Het antwoord is fout',
        ANSWER_CORRECT: 'Hoera! het antwoord is goed!'
      }
      return messages[q.value.state]
    })

    return {
      countdownPromise,
      q,
      loading,
      submit,
      secondsLeft,
      stateMessage
    }
  }
}
</script>
