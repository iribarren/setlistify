<template>
    <div>
        <a class="nav-link dropdown-toggle" href="" id="dropdown09" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><span class="flag-icon flag-icon-us"> </span> {% trans %} Lenguaje {% endtrans %}</a>
        <div class="dropdown-menu" aria-labelledby="dropdown09">
            <a class="dropdown-item" href="#fr">
                <span class="flag-icon flag-icon-fr"> </span>  en
            </a>
            <a class="dropdown-item" href="#fr">
                <span class="flag-icon flag-icon-fr"> </span>  es
            </a>
        </div>
    </div>
</template>

<script>
    export default {
        name: "language-selector",
        props: [],
    }
</script>

<style scoped>
</style>