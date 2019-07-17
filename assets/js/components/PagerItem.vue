<template>
    <li class="page-item">
        <a class="page-link" :href="url+'&page='+id"><slot></slot></a>
    </li>
</template>

<script>
    export default {
        name: "pager-item",
        props: ['url','id']
    }
</script>

<style scoped>
    
</style>
