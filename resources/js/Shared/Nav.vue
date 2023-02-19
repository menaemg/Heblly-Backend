<template>
    <ul>
        <li>1</li>
        <li>2</li>
        <li>3</li>
        <li>4</li>
    </ul>
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



