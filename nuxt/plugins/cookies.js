import Vue from 'vue'
import Cookie from 'cookie'
import JSCookie from 'js-cookie'

const getCookies = (str) => {
  return Cookie.parse(str || '')
}

export default ({ req, isServer }, inject) => {
  inject('cookies', new Vue({
    data: () => ({
      cookies: getCookies(isServer ? req.headers.cookie : document.cookie)
    }),
    methods: {
      set (...args) {
        JSCookie.set(...args)
        this.cookies = getCookies(document.cookie)
      },
      remove (...args) {
        JSCookie.remove(...args)
        this.cookies = getCookies(document.cookie)
      }
    }
  }))
}
