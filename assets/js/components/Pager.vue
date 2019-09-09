<template>
    <nav class="fixed-bottom m-lg-auto">
        <ul class="pagination justify-content-center ">
            <li :class="{'disabled': previousDisabled,'page-item': true}">
                <a class="page-link"  :href="link+'&page='+(actualPage-1)">
                    <span aria-hidden="true">&laquo;</span>
                </a>
            </li>
            <pager-item v-for="n in pages" v-bind:key="n" :id="n" :url="link" :actualPage="actualPage">{{n}}</pager-item>
            <li :class="{'disabled': nextDisabled,'page-item': true}">
                <a class="page-link" :href="link+'&page='+(actualPage+1)">
                    <span aria-hidden="true">&raquo;</span>
                </a>
            </li>
        </ul>
    </nav>
</template>

<script>
    import PagerItem from './PagerItem.vue'
    export default {
        name: "pager",
        components: {
            PagerItem
        },
        props: ['url','page','items-per-page','total'],
        data() {
            return {
                link: this.url,
                actualPage: parseInt(this.page)
            }
        },
        computed: {
            numPages() {
                return Math.ceil(this.total / this.itemsPerPage)
            },
            pages () {
                if (this.actualPage < 3 ) {
                    if (this.numPages < 3) {
                        return Array.from(Array(this.numPages),(x,index) => index +1)
                    } else {
                        return [1,2,3,"..",this.numPages]
                    }
                } else if (this.actualPage > this.numPages-2 ) {
                    return [1,"..",this.numPages-2,this.numPages-1,this.numPages]
                } else {
                    return [1,"..",this.actualPage-1,this.actualPage,this.actualPage+1,"..",this.numPages]
                }
            },
            previousDisabled () {
                return this.actualPage == 1
            },
            nextDisabled () {
                return this.actualPage == this.numPages
            }
        }
    }
</script>

<style>
    .pagination > li > a
    {
        background-color: white;
        color: #5A4181;
    }

    .pagination > li > a:focus,
    .pagination > li > a:hover,
    .pagination > li > span:focus,
    .pagination > li > span:hover
    {
        color: #5a5a5a;
        background-color: #eee;

    }

    .pagination > li > .active 
    {
        color: darkgrey;
        background-color: #5A4181 !Important;
        border-color: #5A4181 !Important;
    }

    .pagination > li > .active:hover
    {
        background-color: #5A4181 !Important;
        color: #FFF !Important;
    }   
</style>
