module.exports = {
  /*
  ** nuxt-modules
  */
  modules: ['@nuxtjs/axios'],
  /*
  ** Axios
  */
  axios: {
    credentials: true,
    baseURL: process.env.API_URL || 'http://192.168.99.100',
    requestInterceptor: (config, { store }) => {
      if (store.state.token) {
        config.headers.common['Authorization'] = `Bearer ${store.state.token}`
      }
      return config
    }
  },
  /*
  ** Plugins
  */
  plugins: [
    { src: '~/plugins/cookies' }
  ]
}
