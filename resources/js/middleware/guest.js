import store from '../store/'
export default function guest({ next, router }) {
    var pin = store.getters.getPIN;
    if (pin) {
        return router.push({ name: "Home" });
    } else {
        return next();
    }
}