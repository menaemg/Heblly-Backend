import { createApp, h } from "vue";
import { InertiaAppProgress } from "@inertiajs/progress";
import { createInertiaApp } from "@inertiajs/vue3";
import { Link } from "@inertiajs/vue3";
import { resolvePageComponent } from "laravel-vite-plugin/inertia-helpers";

// InertiaAppProgress.init()

createInertiaApp({
    resolve: (name) =>
        resolvePageComponent(
            `./Pages/${name}.vue`,
            import.meta.glob("./Pages/**/*.vue")
        ),
    setup({ el, App, props, plugin }) {
        return createApp({ render: () => h(App, props) })
            .mixin({ methods: { route }})
            .use(plugin)
            .mount(el);
    },
});
