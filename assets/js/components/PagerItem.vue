<template>
    <li v-if="link" class="page-item">
        <a :class="{'active': active,'page-link': true}" :href="link"> 
            <slot></slot>
        </a>
    </li>
    <li v-else class="page-item">
        <p class="page-link">
        <slot></slot>
        </p>
    </li>
</template>

<script>
    export default {
        name: "pager-item",
        props: ['url','id','actualPage'],
        computed: {
            active () {
                return this.id == this.actualPage
            },
            link () {
                if (this.id != ".."){
                    return this.url+'&page='+this.id
                } else {
                    return false;
                }
            }
        }
    }
</script>

<style scoped>
    
</style>
