import { ref, computed } from 'vue'

const countdown = (endDate) => {
  if (!endDate) return

  const t = endDate.split(/[- :]/)
  const countdownDate = new Date(Date.UTC(t[0], t[1] - 1, t[2], t[3], t[4], t[5]))
  const now = Date.now()

  const diff = (countdownDate - now)
  const timeLeft = ref(diff)

  const promise = new Promise((resolve) => {
    const countdownInterval = window.setInterval(() => {
      if (timeLeft.value <= 0) {
        clearInterval(countdownInterval)
        resolve()
      }
      timeLeft.value = timeLeft.value - 1000
    }, 1000)
  })

  const secondsLeft = computed(() => Math.max(Math.round(timeLeft.value / 1000), 0))

  return {
    secondsLeft,
    promise
  }
}

export default countdown
