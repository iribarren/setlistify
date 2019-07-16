<template>
    <nav>
        <ul class="pagination justify-content-center ">
            <li class="page-item"><a class="page-link" :href="link+'&page='+(actualPage-1)">Previous</a></li>
                <pager-item v-for="n in pages" v-bind:key="n" :id="n" :url="link">{{n}}</pager-item>
            <li class="page-item"><a class="page-link" :href="link+'&page='+(actualPage+1)">Next</a></li>
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
                return Math.round(this.total / this.itemsPerPage)
            },
            pages () {
                if (this.numPages > 10 ) {
                    return [1,2,'..',this.page,'..',9, 10]
                }
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

    .pagination > .active > a
    {
        color: darkgrey;
        background-color: #5A4181 !Important;
        border-color: #5A4181 !Important;
    }

    .pagination > .active > a:hover
    {
        background-color: #5A4181 !Important;   
    }   
</style>
