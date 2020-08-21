import store from '../store/'
export default function auth({ next, router }) {
    var pin = store.getters.getPIN;
    if (pin) {
        return next();
    } else {
        return router.push({ name: "Login" });
    }
}