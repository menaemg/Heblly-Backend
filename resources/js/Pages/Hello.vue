<template>
    <nav>
        <ul>
            <li><Link href="/hello">Hello</Link></li>
            <li><Link href="/">Index</Link></li>
        </ul>
    </nav>

    <h2>
        Hello {{ name }}
    </h2>

    <Nav></Nav>

</template>

<script>
import { createInertiaApp, Head, Link } from "@inertiajs/vue3";
import Nav from "@/Shared/Nav.vue";

export default {
    components: {
        Link,
        Nav
    },
    props: {
        name: String,
    },
    data() {
        return {}
    }
};
</script>
