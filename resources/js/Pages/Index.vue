<template>
    <nav>
        <ul>
            <li><Link href="/hello">Hello</Link></li>
            <li><Link href="/">Index</Link></li>
        </ul>
    </nav>

    <h2>
        Index {{ name }}
    </h2>

</template>

<script>
import { createInertiaApp, Head, Link } from "@inertiajs/vue3";

export default {
    components: {
        Link
    },
    props: {
        name: String,
    },
    data() {
        return {}
    }
};
</script>
