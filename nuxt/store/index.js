const inBrowser = typeof window !== 'undefined'

export const state = () => {
  return {
    user: null,
    loggedIn: false,
    token: null
  }
}

export const getters = {}

export const mutations = {
  setUser (state, { user }) {
    state.user = user
    state.loggedIn = Boolean(user)
  },

  setToken (state, { token }) {
    state.token = token

    // Store token in cookies
    if (inBrowser) {
      if (token) {
        this.$cookies.set('token', token, { expires: 30 })
      } else {
        this.$cookies.remove('token')
      }
    }
  }
}

export const actions = {
  nuxtServerInit ({ dispatch, state, commit }, { error }) {
    const token = this.$cookies.cookies.token

    if (!token) {
      return Promise.resolve()
    }

    return dispatch('fetchUserByAccessToken', { token }).catch(e => {
      return dispatch('logout').catch(e => {
        error({ message: e.message, statusCode: e.statusCode })
      })
    })
  },

  fetchUserByPasswordGrant ({ commit, dispatch }, { email, password }) {
    const params = {
      grant_type: 'password',
      client_id: process.env.clientId,
      client_secret: process.env.clientSecret,
      username: email,
      password: password,
      scope: '*'
    }

    return this.$axios.$post('/oauth/token', params).then(data => {
      return dispatch('fetchUserByAccessToken', { token: data.access_token })
        .catch(e => Promise.reject(e))
    })
  },

  fetchUserByAccessToken ({ commit, dispatch }, { token }) {
    commit('setToken', { token })

    return this.$axios.$get('/users/@me').then(user => {
      commit('setUser', { user })
    })
  },

  logout ({ commit }) {
    commit('setUser', { user: null })

    // Revoke access token
    return this.$axios.delete('/oauth/token/destroy').then(() => {
      commit('setToken', { token: null })
    }).catch(e => {
      commit('setToken', { token: null })
    })
  }
}
