<template>
  <p>Twitterへリダイレクトしています</p>
</template>

<script>
export default {
  middleware: 'guest',

  asyncData ({ app, error }) {
    return app.$axios.$get('/oauth/twitter/redirect')
      .then(data => {
        return { twitterAuthUrl: data.redirect_url }
      })
      .catch(e => error({ message: e.message, statusCode: e.statusCode }))
  },

  mounted () {
    window.location.href = this.twitterAuthUrl
  }
}
</script>
