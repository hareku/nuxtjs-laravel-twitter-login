export default function ({ store, redirect }) {
  if (store.state.loggedIn) {
    return redirect('/')
  }
}
